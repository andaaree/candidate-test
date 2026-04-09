<?php
namespace App\Services;

use App\Models\Supplier;
use App\Contracts\SuppliersInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierService implements SuppliersInterface{
    public function paginate(int $perPage = 10):LengthAwarePaginator {
        return Supplier::query()
            ->withCount('layups')
            ->latest()
            ->paginate($perPage);
    }

    public function store(array $data) {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data) {
        $supplier->update($data);
        return $supplier;
    }

    public function delete(Supplier $supplier) {
        $supplier->delete();
    }
}
