<?php
namespace App\Services;

use App\Models\Supplier;
use App\Contracts\SuppliersInterface;
use App\Exports\SupplierExport;
use App\Models\Layer;
use App\Models\Layup;
use App\Traits\FeedbackHandler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    public function showDetailedSupplier(Supplier $supplier)
    {
        try {
            return $supplier->with('layers','layups')->find($supplier->id);
        } catch (\Throwable $th) {
            return $this->err(Supplier::class,$th);
        }
    }

    public function showLayerAssoc(Supplier $supplier)
    {
        try {
            return $supplier->withCount('layups as total_layups')
              ->with(['layups' => function ($query) {
                $query->withSum('layers', 'thickness')
                ->withCount('layers as total_layers')
                ->with('layers')
                ->get();}
                ])
            ->find($supplier->id);
        }catch(\Exception $th){
            return $this->err(Supplier::class,$th);
        }
    }

    public function setBreadcrumb($class) {
        return $this->defaultNav(request()->path());
    }


    public function export($mode) {
        try {
          return Excel::download(new SupplierExport($mode), 'suppliers_'.now('Asia/Jakarta').'.xlsx');
      } catch (\Exception $th) {
          return $this->err(Supplier::class,$th);
      }
    }

    public function parse($file): array
    {
        if ($file->getClientOriginalExtension() === 'json') {
            return json_decode(file_get_contents($file->getRealPath()), true);
        }

        return Excel::toArray([], $file)[0];
    }

    public function detectConflicts(array $data): array
    {
        $conflicts = [];

        foreach ($data as $supplier) {
            foreach ($supplier['layups'] ?? [] as $layup) {
                foreach ($layup['layers'] ?? [] as $layer) {
                    $existing = Layer::whereHas('layup', function ($q) use ($supplier, $layup) {
                        $q->where('name', $layup['name'])
                        ->whereHas('supplier', fn($s) => $s->where('name', $supplier['name']));
                    })->where('layer_order', $layer['layer_order'])->first();

                    if ($existing) {
                        if (
                            $existing->thickness != $layer['thickness'] ||
                            $existing->width != $layer['width'] ||
                            $existing->angle != $layer['angle']
                        ) {
                            $conflicts[] = [
                                'supplier' => $supplier['name'],
                                'layup' => $layup['name'],
                                'layer_order' => $layer['layer_order'],
                                'existing' => $existing->toArray(),
                                'incoming' => $layer,
                                'decision' => null,
                            ];
                        }
                    }
                }
            }
        }

        return $conflicts;
    }
    public function applyResolvedImport(array $draft): array
    {
        $payload = $draft['payload'];
        $conflicts = collect($draft['conflicts']);

        $result = [
            'created' => [],
            'updated' => [],
            'applied_conflicts' => [],
        ];

        DB::transaction(function () use ($payload, $conflicts, &$result) {

            foreach ($payload as $supplierData) {

                $supplier = Supplier::firstOrCreate([
                    'name' => $supplierData['name']
                ]);

                foreach ($supplierData['layups'] as $layupData) {

                    $layup = Layup::firstOrCreate([
                        'supplier_id' => $supplier->id,
                        'name' => $layupData['name']
                    ]);

                    foreach ($layupData['layers'] as $layerData) {

                        $key = md5($layup->name.'-'.$layerData['layer_order']);

                        $conflict = $conflicts->first(fn($c) => md5($c['layup'].'-'.$c['layer_order']) === $key);

                        $existing = Layer::where('layup_id', $layup->id)
                            ->where('layer_order', $layerData['layer_order'])
                            ->first();

                        if ($conflict && $conflict['decision'] === 'keep_existing') {
                            continue;
                        }

                        if ($conflict && $conflict['decision'] === 'accept_incoming') {

                            if ($existing) {
                                $existing->update($layerData);
                                $result['applied_conflicts'][] = $key;
                            } else {
                                Layer::create([
                                    'layup_id' => $layup->id,
                                    ...$layerData
                                ]);
                            }

                            continue;
                        }

                        // normal flow (no conflict)
                        if (!$existing) {
                            Layer::create([
                                'layup_id' => $layup->id,
                                ...$layerData
                            ]);
                        }
                    }
                }
            }
        });

        return $result;
    }

    public function resolveLastSupplier(array $payload)
    {
        $name = collect($payload)->last()['name'] ?? null;
        return Supplier::where('name', $name)->first();
    }
}
