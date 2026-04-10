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
            return $this->err('error',$th);
        }
    }

    public function showDetailedLayup(Layup $layup)
    {
        try {
            $layup = $layup->withSum('layers.thickness as total_thickness','layers as total_layers')->get();
            return $layup;
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
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
            return $this->gd('success',$layup);
        } catch (\Exception $th) {
            return $this->err('error',$th);
        }
    }
}
