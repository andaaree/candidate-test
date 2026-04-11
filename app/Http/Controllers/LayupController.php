<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayupStoreRequest;
use App\Http\Requests\LayupUpdateRequest;
use App\Models\Layup;
use App\Services\LayupService;

class LayupController extends Controller
{
    public function __construct(
        protected LayupService $service
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Layup $layup)
    {
        $layup = $layup->find(2);
        return $this->service->showDetailedLayup($layup);
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
    public function store(LayupStoreRequest $request)
    {
        $res = $this->service->store($request->validated());
        return $res;
        // return redirect()->back()->with($res);
    }

    /**
     * Display the specified resource.
     */
    public function show(Layup $layup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layup $layup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LayupUpdateRequest $request, Layup $layup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layup $layup)
    {
        //
    }
}
