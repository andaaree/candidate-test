<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Layer::paginate(10)->with(['layup', function(Builder $q){
            $q->with('supplier');
        }])->get();
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Layer $layer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layer $layer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layer $layer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layer $layer)
    {
        //
    }
}
