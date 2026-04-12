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

    public function getAll(){
        return Supplier::all();
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
            $supplier->name = $data['supplier_name'];
            $supplier->save();

            $res = $this->message($supplier,'updated');
            return redirect()->route('supplier.index')->with($res->status,json_encode($res));
        } catch (\Exception $th) {
            $res = $this->err(Supplier::class,$th);
            return redirect()->route('supplier.index')->with($res->status,json_encode($res));
        }
    }

    public function delete(Supplier $supplier) {
      try {
        if ($supplier->layups()->exists()) {
          return $this->message($supplier,null,'Supplier have layups in it!');
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

    public function parse($filePath): array
    {
        if ($filePath === 'json') {
            return json_decode(file_get_contents($filePath), true);
        }

        return Excel::toArray([], $filePath)[0];
    }

    public function detectConflicts(array $data): array
    {
        $conflicts = [];
        foreach ($data as $d) {
            $existing = Layup::where('name', $d['layup_name'])
                ->with('supplier', 'layers')
                ->first();
            if (! $existing) continue;
            // find the correct layer by order
            $layer = $existing->layers
                ->firstWhere('layer_order', $d['layer_order']);
            // ONLY if both layers exist
            if (! $layer) continue;

            // detect difference (real conflict)
            if (
                (float)$layer->thickness !== (float)$d['thickness'] ||
                (float)$layer->width !== (float)$d['width'] ||
                (float)$layer->angle !== (float)$d['angle']
            ) {
                $conflicts[] = [
                    'supplier_id' => $d['supplier_id'],
                    'supplier_name' => $d['supplier_name'],
                    'layup' => $d['layup_name'],
                    'layer_order' => $d['layer_order'],
                    'existing' => [
                        'id' => $layer->id,
                        'thickness' => (float)$layer->thickness,
                        'width' => (float)$layer->width,
                        'angle' => (float)$layer->angle,
                    ],
                    'incoming' => [
                        'layer_order' => $d['layer_order'],
                        'thickness' => (float)$d['thickness'],
                        'width' => (float)$d['width'],
                        'angle' => (float)$d['angle'],
                    ],
                    'decision' => null,
                ];
            }
        }
        return $conflicts;
    }

    public function resolveConflicts(array $conflicts)
    {
        try {
            $updated = [];
            foreach ($conflicts as $c) {
                $layup = Layup::where('name', $c['layup'])
                    ->where('supplier_id', $c['supplier_id'])
                    ->first();
                if (!$layup) continue;
                // find layer (ONLY existing layer)
                $layer = Layer::where('layup_id', $layup->id)
                ->where('layer_order', $c['layer_order'])
                ->first();
                if (!$layer) continue;
                //  overwrite with incoming
                $layer->update([
                    'thickness' => $c['incoming']['thickness'],
                    'width'     => $c['incoming']['width'],
                    'angle'     => $c['incoming']['angle'],
                ]);
                $updated[] = [
                    'layer_id' => $layer->id,
                    'layup' => $c['layup'],
                    'layer_order' => $c['layer_order'],
                ];
            }
            return $this->message(Layer::class,'updated');
        } catch (\Exception $e) {
            return $this->err(Layer::class,$e);
        }
    }

    public function saveImported(array $data){
        try {
            $sp = [];
            $lp = [];
            $ly = [];
            foreach ($data as $key => $d) {
                $sp = Supplier::firstOrCreate(['name' => $d['supplier_name']]);
                $lp = Layup::firstOrCreate(['name'=> $d['layup_name'], 'supplier_id' => $sp->id ]);
                $ly = Layer::firstOrCreate([
                    ['layup_id' => $lp->id, "layer_order" => $d['layer_order'] ],
                    ["thickness" => $d['thickness'] , "width" => $d['width'] ,
                    "angle" => $d['angle'] ]
                ]);
            }
            $res = $this->message(null,null,"Import unkown. $sp <br> $lp <br> $ly");
            return $res;
        } catch (\Exception $th) {
            return $this->err(Supplier::class,$th);
        }
    }

    public function resolveLastSupplier(array $payload)
    {
        $name = collect($payload)->last()['supplier_name'] ?? null;
        return Supplier::where('name', $name)->first();
    }
    public function mappingData(array $data,$supplier):array {

        if (empty($data) || count($data) < 2) {
            return [];
        }
        $headers = $data[0]; // first row = header
        $mapped = [];

        foreach ($data as $i => $row) {
            if ($i === 0) {
                continue; // skip header
            }
            $item = [];
            $item['supplier_id'] = $supplier->id;
            $item['supplier_name'] = $supplier->name;
            foreach ($headers as $index => $key) {
                $item[$key] = $row[$index] ?? null;
            }
            $mapped[] = $item;
        }
        return $mapped;
    }
}
