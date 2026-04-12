<?php

namespace App\Contracts;

use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LayupInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function createForSupplier(int $supplierId, array $data);
    public function showDetailedLayup(Layup $layup);
    public function showLayerAssoc(Layup $layup);
    public function store(int $supplierId,array $data);
    public function updateForSupplier(int $supplierId,Layup $layup, array $data);
    public function deleteForSupplier(int $supplierId,Layup $layup);
    public function exportBySupplier(Supplier $supplier);
    public function setBreadcrumb($class);
}
