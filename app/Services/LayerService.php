<?php

namespace App\Services;

use App\Contracts\LayerInterface;
use App\Models\Layer;
use App\Models\Layup;
use App\Traits\FeedbackHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LayerService implements LayerInterface{
    use FeedbackHandler;
    public function paginate(int $perPage = 10): LengthAwarePaginator {
        return Layer::query()->paginate($perPage);
    }
    public function createForLayer(int $layerId, array $data) {
        //
    }
    public function showDetailedLayer(Layer $layer) {
        //
    }
    public function showLayerAssoc(Layer $layer) {
        //
    }
    public function store(int $layerId,array $data) {
        try {
            if ($layerId != $data['layup_id']) {
                return $this->err(Layup::class,new \Exception('Layup tidak ditemukan'));
            }
            $layer = Layer::create($data);
            return $this->message($layer,'created');
        } catch (\Exception $th) {
            return $this->err(Layup::class,$th);
        }
    }

    public function updateForLayer(int $layupId,Layer $layer, array $data) {
        try {
            $getLayerID = $layer->layup->id ?? null;
            if ($getLayerID != $layupId) {
                return $this->err(Layup::class,new \Exception('Layup tidak ditemukan'));
            }
            $layer->update($data);
            return $this->message($layer,'updated');
        } catch (\Throwable $th) {
            return $this->err(Layup::class,$th);
        }
    }

    public function deleteForLayer(int $layupId,Layer $layer) {
        //
    }


    public function setBreadcrumb($class) {
        return $this->defaultNav($class,request()->path());
    }
}
