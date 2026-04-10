<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use App\Traits\FeedbackHandler;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use FeedbackHandler;
    public function __construct(protected SupplierService $service)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia()->render('Supplier/Index',[
            'suppliers' => [$this->service->paginate()]
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
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
}
