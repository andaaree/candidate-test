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
            return $this->err(Supplier::class,$th);
        }
    }

    public function update(Supplier $supplier, array $data) {
        try {
            $supplier->update($data);
            return $supplier;
            } catch (\Exception $th) {
                return $this->err(Supplier::class,$th);
            }
        }

    public function delete(Supplier $supplier) {
      try {
        if ($supplier->layups()->exists()) {
          return $this->message($supplier,null,'Supplier  layup');
        }
        $supplier->delete();
        return $this->message($supplier,null,'Supplier deleted successfully');
      } catch (\Exception $th) {
        return $this->err(Supplier::class,$th);
      }
    }
}
