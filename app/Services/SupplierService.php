<?php
namespace App\Services;

use App\Models\Supplier;
use App\Contracts\SuppliersInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Traits\GlobalWarn;
class SupplierService implements SuppliersInterface{
    use GlobalWarn;

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
            return $this->message('error',$th->getMessage());
        }
    }

    public function update(Supplier $supplier, array $data) {
        try {
            $supplier->update($data);
            return $supplier;
        } catch (\Exception $th) {
            return $this->message('error',$th->getMessage());
        }
    }

    public function delete(Supplier $supplier) {
        try {
            if ($supplier->layups()->exists()) {
                return $this->message('error','Supplier masih memiliki layup');
            }
            $supplier->delete();
            return $this->message('success','Supplier berhasil dihapus');
        } catch (\Exception $e) {
            return $this->message('error',$e->getMessage());
        }
    }
}
