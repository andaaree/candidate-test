<?php

namespace App\Http\Controllers;

use App\Models\Layup;
use App\Services\LayupService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LayupController extends Controller
{
    public function __construct(protected LayupService $layupService)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Layup/Index');
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
    public function update(Request $request, Layup $layup)
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
