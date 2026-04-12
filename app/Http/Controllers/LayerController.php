<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayerStoreRequest;
use App\Http\Requests\LayerUpdateRequest;
use App\Models\Layer;
use App\Models\Layup;
use App\Services\LayerService;
use Illuminate\Http\Request;

class LayerController extends Controller
{
    protected $items = [];

    public function __construct(
        protected LayerService $service
    ) {$this->items = $this->service->setBreadcrumb(request()->path());}
    /**
     * Display a listing of the resource.
     */
    public function index(Layup $layup)
    {
        return redirect('/supplier');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Layup $layup)
    {
        $exist = Layer::where('layup_id',$layup->id)->get(['layer_order','thickness']);
        return view('pages.layer.create',[
            'last_uri' => request()->header('referer'),
            'layup' => $layup,
            'exist' => $exist,
            'title' => 'Create Layer',
            'pageName' => 'Create Layer',
            'items' => $this->items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Layup $layup,LayerStoreRequest $request)
    {
        $back = $request->last_uri;
        $res = $this->service->store($layup->id,$request->validated());
        return redirect($back)->with($res->status,json_encode($res));
    }

    /**
     * Display the specified resource.
     */
    public function show(Layup $layup,Layer $layer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layup $layup,Layer $layer)
    {
        $exist = Layer::where('layup_id',$layup->id)->get(['layer_order','thickness']);
        return view('pages.layer.edit',[
            'layup' => $layup,
            'last_uri' => request()->header('referer'),
            'layer' => $layer,
            'exist' => $exist,
            'title' => 'Edit Layer',
            'pageName' => 'Edit Layer',
            'items' => $this->items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LayerUpdateRequest $request,Layup $layup, Layer $layer)
    {
        $back = $request->last_uri;
        $res = $this->service->updateForLayer($layup->id,$layer,$request->validated());
        return redirect($back)->with($res->status,json_encode($res));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layup $layup,Layer $layer)
    {
        //
    }
}
