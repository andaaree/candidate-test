<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayupStoreRequest;
use App\Http\Requests\LayupUpdateRequest;
use App\Models\Layup;
use App\Models\Supplier;
use App\Services\LayupService;
use Database\Factories\LayupFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory(LayupFactory::class)]
class LayupController extends Controller
{
    protected $items = [];

    public function __construct(
        protected LayupService $service
    ) {$this->items = $this->service->setBreadcrumb(request()->path());}
    /**
     * Display a listing of the resource.
     */
    public function index(Supplier $supplier, Layup $layup)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Supplier $supplier)
    {
        return view('pages.layup.create',[
            'supplier' => $supplier,
            'items' => $this->items,
            'title' => 'Create Layup',
            'pageName' => 'Create Layup'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Supplier $supplier,LayupStoreRequest $request)
    {
        $res = $this->service->store($supplier->id,$request->validated());
        return redirect()->route('supplier.show',$supplier->id)->with($res->status,json_encode($res));
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier,Layup $layup)
    {
        // return $this->service->showDetailedLayup($layup);
        return view('pages.layup.show',[
            'supplier' => $supplier,
            'layup' => $this->service->showDetailedLayup($layup),
            'items' => $this->items,
            'title' => 'Layup Detail',
            'pageName' => 'Layup Detail'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier,Layup $layup)
    {
        return view('pages.layup.edit',[
            'supplier' => $supplier,
            'layup' => $layup,
            'title' => 'Edit Layup',
            'pageName' => 'Edit Layup',
            'items' => $this->items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LayupUpdateRequest $request,Supplier $supplier, Layup $layup)
    {
        $back = $request->last_path;
        $res = $this->service->updateForSupplier($supplier->id,$layup,$request->validated());
        return redirect()->route('supplier.show',$supplier->id)->with($res->status,json_encode($res));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layup $layup)
    {
        //
    }

    public function export(Supplier $supplier){
        return $this->service->exportBySupplier($supplier);
    }
}
