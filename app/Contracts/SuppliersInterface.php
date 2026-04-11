<?php

namespace App\Contracts;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SuppliersInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function store(array $data);
    public function update(Supplier $supplier, array $data);
    public function delete(Supplier $supplier);
    public function export(array $supplier);
    public function setBreadcrumb();
    public function import();
}
