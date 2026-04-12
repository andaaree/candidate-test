<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierImportRequest;
use App\Http\Requests\SupplierStoreRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use App\Traits\FeedbackHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    use FeedbackHandler;
    protected $items = [];

    public function __construct(
        protected SupplierService $service
    ) {
        $this->items = $this->service->setBreadcrumb(request()->path());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return $this->service->paginate();
        return view('pages.supplier.index',[
            'suppliers' => $this->service->paginate(),
            'title' => 'Supplier Detail',
            'pageName' => 'Supplier Detail',
            'items' => $this->items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreRequest $request)
    {
        $res = $this->service->store($request->validated());
        return $res;
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        // return $this->service->showLayerAssoc($supplier);
        return view('pages.supplier.show',[
            'supplier' => $this->service->showLayerAssoc($supplier),
            'title' => 'Supplier Detail',
            'pageName' => 'Supplier Detail',
            'items' => $this->items
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }

    public function export(Request $request)
    {
        $mode = $request->mode;
        return $this->service->export($mode);
    }

    public function import(){
        return view('pages.supplier.import',[
            'items' => $this->items,
            'title' => 'Import Supplier',
            'pageName' => 'Import Supplier'
        ]);
    }

    public function uploadFile(SupplierImportRequest $request)
    {
        $file = $request->file('file');

        $path = $file->store('public/');

        return response()->json([
            'file_token' => $path, // simple token
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file_token' => ['required', 'string'],
        ]);
        $path = $request->input('file_token');
        abort_if(!Storage::exists($path), 404, 'File not found');

        $fullPath = storage_path("app/" . $path);
        $parsed = $this->service->parse($fullPath);
        $sessionId = (string) Str::uuid();
        $conflicts = $this->service->detectConflicts($parsed);

        session([
            "import_draft.$sessionId" => [
                'payload' => $parsed,
                'conflicts' => $conflicts,
            ]
        ]);

        // no conflict -> auto import
        if (empty($conflicts)) {

            $this->service->applyResolvedImport([
                'payload' => $parsed,
                'conflicts' => []
            ]);
            // cleanup
            session()->forget("import_draft.$sessionId");
            Storage::delete($path);
            $supplier = $this->service->resolveLastSupplier($parsed);
            return redirect()
                ->route('supplier.show', $supplier?->id)
                ->with('success', 'Import completed successfully.');
        }

        // HAS CONFLICT -> REVIEW PAGE
        return redirect("/supplier/import/review/$sessionId");
}

    public function review($sessionId)
    {
        $draft = session("import_draft.$sessionId");
        abort_if(!$draft, 404);

        return view('import.review', [
            'sessionId' => $sessionId,
            'draft' => $draft
        ]);
    }

    public function resolve(Request $request, $sessionId)
    {
        $draft = session("import_draft.$sessionId");
        abort_if(!$draft, 404);
        $decisions = $request->input('decisions', []);

        foreach ($draft['conflicts'] as &$conflict) {
            $key = $this->conflictKey($conflict);

            if (isset($decisions[$key])) {
                $conflict['decision'] = $decisions[$key];
            }
        }

        session(["import_draft.$sessionId" => $draft]);

        return response()->json(['status' => 'ok']);
    }

    public function commit(string $sessionId)
    {
        $draft = session("import_draft.$sessionId");
        abort_if(!$draft, 404);

        // Execute import
        $result = $this->service->applyResolvedImport($draft);
        // Cleanup session
        session()->forget("import_draft.$sessionId");

        // Resolve redirect target (last supplier)
        $lastSupplierName = collect($draft['payload'])->last()['name'] ?? null;
        $supplier = Supplier::where('name', $lastSupplierName)->first();

        // Simple, readable message (no extra function)
        $created = count($result['created'] ?? []);
        $updated = count($result['updated'] ?? []);
        $resolved = count($result['applied_conflicts'] ?? []);

        $res = $this->message(Supplier::class,"Created: {$created}, Updated: {$updated}, Resolved: {$resolved}");
        return redirect()
            ->route('suppliers.show', $supplier?->id)
            ->with($res->status,$res);
    }

    private function conflictKey($c)
    {
        return md5($c['layup'].'-'.$c['layer_order']);
    }

}
