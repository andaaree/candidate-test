<?php
namespace App\Services;

use App\Models\Supplier;
use App\Contracts\SuppliersInterface;
use App\Traits\FeedbackHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class SupplierService implements SuppliersInterface{
    use FeedbackHandler;

    public function paginate(int $perPage = 10):LengthAwarePaginator {
        return Supplier::query()
            ->withCount('layups')
            ->latest()
            ->paginate($perPage);
    }

    public function store(array $data) {
        try {
            return Supplier::create($data);
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
    }

    public function update(Supplier $supplier, array $data) {
        try {
            $supplier->update($data);
            return $supplier;
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
    }

    public function delete(Supplier $supplier) {
        try {
            if ($supplier->layups()->exists()) {
                return $this->err('error',new \Exception('Supplier masih memiliki layup'));
            }
            $supplier->delete();
            return $this->gd('success',$supplier);
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
    }
}
