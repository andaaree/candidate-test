<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierImportRequest;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
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
        return redirect()->route('supplier.index')->with($res->status,json_encode($res));
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
        return view('pages.supplier.edit',[
            'supplier' => $supplier,
            'title' => 'Edit Supplier',
            'pageName' => 'Edit Supplier',
            'items' => $this->items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, Supplier $supplier)
    {
        return $this->service->update($supplier,$request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $res = $this->service->delete($supplier);
        return response()->json($res);
    }

    public function export(Request $request)
    {
        $mode = $request->mode;
        return $this->service->export($mode);
    }

    public function import(Supplier $supplier)
    {
        return view('pages.supplier.import',[
            'supplier' => $supplier,
            'items' => $this->items,
            'title' => 'Import Supplier',
            'pageName' => 'Import Supplier'
        ]);
    }

    public function importFile(Request $request, Supplier $supplier) {
        $request->validate([
            'imported' => ['required','file','extensions:xlsx,csv,json','max:1024000'],
            'mode' => 'required|in:accept_incoming,keep_existing'
        ]);
        $file = $request->file('imported');
        if ($request->hasFile('imported')) {
            $fileType = $request->file('imported')->getClientOriginalExtension();
        }
        $filename = 'supplier_import_' . now('Asia/Jakarta')->format('Ymd_His') .'.'. $fileType;
        $path = $file->storeAs('public', $filename);
        $fullPath = storage_path("app/" . $path);

        $parsed = $this->service->parse($file);
        $parsed = $this->service->mappingData($parsed,$supplier);
        $conflicts = $this->service->detectConflicts($parsed);
        $supplier = $this->service->resolveLastSupplier($parsed);

        $sessionId = (string) Str::uuid();
        session([
            "import_draft.$sessionId" => [
                'payload' => $parsed,
                'conflicts' => $conflicts,
            ]
        ]);
        if (empty($conflicts)) {
            // cleanup
            Storage::delete($path);
            session()->forget("import_draft.$sessionId");
            $res = $this->service->saveImported($parsed);
        }
        if ($request->mode == 'review') {
            // HAS CONFLICT -> REVIEW PAGE
            $sessionId = (string) Str::uuid();
            return redirect("/supplier/import/review/$sessionId");
        }
        // Has Conflict -> overwrite
        $res = $this->service->resolveConflicts($conflicts);

        return redirect()
            ->route('supplier.show', $supplier?->id)
            ->with($res->status, $res->message);
    }

    public function review($sessionId)
    {
        $draft = session("import_draft.$sessionId");
        abort_if(!$draft, 404);

        return view('pages.review', [
            'sessionId' => $sessionId,
            'draft' => $draft,
            'title' => 'Supplier Import Review',
            'pageName' => 'Supplier Import Review',
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
    private function conflictKey($c)
    {
        return md5($c['layup'].'-'.$c['layer_order']);
    }

}
