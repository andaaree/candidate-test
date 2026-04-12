<?php

namespace App\Contracts;

use App\Models\Layer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LayerInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function createForLayer(int $supplierId, array $data);
    public function showDetailedLayer(Layer $layer);
    public function showLayerAssoc(Layer $layer);
    public function store(int $supplierId,array $data);
    public function updateForLayer(int $layupId,Layer $layer, array $data);
    public function deleteForLayer(int $layupId,Layer $layer);
    public function setBreadcrumb($class);
}
