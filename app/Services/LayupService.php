<?php

namespace App\Services;

use App\Contracts\LayupInterface;
use App\Models\Layup;
use App\Traits\FeedbackHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
            ->with('layers','supplier')
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
    public function updateForSupplier(int $supplierId,Layup $layup, array $data)
    {
        try {
            $getSupID = $layup->supplier->id ?? null;
            if ($getSupID != $supplierId) {
                return $this->err('error', new \Exception('Supplier tidak ditemukan'));
            }
            $layup->update($data);
            return $layup;
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
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
}
