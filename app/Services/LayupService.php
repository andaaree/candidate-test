<?php

namespace App\Services;

use App\Contracts\LayupInterface;
use App\Exports\LayupExport;
use App\Models\Layup;
use App\Models\Supplier;
use App\Traits\FeedbackHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class LayupService implements LayupInterface{
    use FeedbackHandler;

    public function paginate(int $perPage = 10):LengthAwarePaginator {
        return Layup::query()
            ->withCount('layers')
            ->latest()
            ->paginate($perPage);
    }
    public function createForSupplier(int $supplierId, array $data) {
        try {
            $data['supplier_id'] = $supplierId;
            return Layup::create($data);
        } catch (\Exception $th) {
            return $this->err(Layup::class,$th);
        }
    }

    public function showDetailedLayup(Layup $layup)
    {
        try {
            $layup = $layup->withSum('layers','thickness')
            ->withCount('layers as total_layers')
            ->with(['layers' => function($q){$q->orderBy('layer_order');}],'supplier')
            ->find($layup->id);
            return $layup;
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
    }

    public function showLayerAssoc(Layup $layup)
    {
        try {
            return $layup->with('layers','supplier')->find($layup->id);
        } catch (\Exception $th) {
            return $this->err(Layup::class,$th);
        }
        throw new \Exception('Not implemented');
    }

    public function store(int $supplier, array $data) {
        try {
            if ($supplier != $data['supplier_id']) {
                return $this->err(Layup::class,new \Exception('Supplier tidak ditemukan'));
            }
            $lay = new Layup;
            $lay->supplier_id = $supplier;
            $lay->name = $data['layup_name'];
            $lay->save();
            return $this->message($lay,'created');
        } catch (\Throwable $th) {
            return $this->err(Layup::class,$th);
        }
    }

    public function updateForSupplier(int $supplierId,Layup $layup, array $data)
    {
        try {
            $getSupID = $layup->supplier->id ?? null;
            if ($getSupID != $supplierId) {
                return $this->err('error', new \Exception('Supplier tidak ditemukan'));
            }
            $layup->name = $data['layup_name'];
            $layup->update($data);
            return $this->message($layup,'updated');
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
    }

    public function setBreadcrumb($class) {
        return $this->defaultNav($class,request()->path());
    }

    public function deleteForSupplier(int $supplierId, Layup $layup)
    {
        try {
            $getSupID = $layup->supplier->id ?? null;
            if ($getSupID !== $supplierId) {
                return $this->err('error',new \Exception($layup));
            }
            $layup->delete();
            return $this->message($layup,null,'Layup deleted successfully');
        } catch (\Exception $th) {
            return $this->err(Layup::class,$th);
        }
    }

    public function exportBySupplier(Supplier $supplier)
    {
        try {
            return Excel::download(new LayupExport($supplier->id), 'layups_'.now('Asia/Jakarta')->format('Ymd_His').'.xlsx');
        }catch(\Exception $th){
            return $this->err(Layup::class,$th);
        }
    }

    public function importBySupplier(int $supplierId, string $strategy)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
