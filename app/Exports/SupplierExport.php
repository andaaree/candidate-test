<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromCollection, WithHeadings, WithMapping
{
    protected $mode;
    protected $supplierIds;

    public function __construct(int $mode)
    {
        $this->mode = $mode;
        $this->supplierIds = [];
    }

    public function collection()
    {
        if ($this->mode == 1) {
            $supplier = Supplier::with('layups.layers')
                ->withCount('layups as total_layups')->get();
        }else{
            $suppliers = Supplier::query()
                ->withCount('layups')
                ->latest()
                ->paginate(10);
            foreach ($suppliers as $key => $s) {
                $this->supplierIds[] = $s->id;
            }
            $supplier = Supplier::whereIn('id', $this->supplierIds)
                ->with('layups.layers')
                ->withCount('layups as total_layups')
                ->get();
        }
            return $supplier;
    }

    public function headings(): array
    {
        return [
            'Supplier Name',
            'Layup Name',
            'Layer Order',
            'Thickness',
            'Width',
            'Angle',
        ];
    }

    public function map($supplier): array
    {
        $rows = [];
        if ($supplier->total_layups > 0) {
            foreach ($supplier->layups as $layup) {
                if (count($layup->layers) > 0) {
                    foreach ($layup->layers as $layer) {
                        $rows[] = [
                            $supplier->name,
                            $layup->name,
                            $layer->layer_order,
                            $layer->thickness."mm",
                            $layer->width."mm",
                            $layer->angle,
                        ];
                    }
                }else{
                    $rows[] = [
                        $supplier->name,
                        $layup->name,
                        'N/A',
                        'N/A',
                        'N/A',
                        'N/A',
                    ];
                }
            }
        }else{
            $rows[] = [
                $supplier->name,
                'No Layup',
                'N/A',
                'N/A',
                'N/A',
                'N/A',
            ];
        }
        return collect($rows)->toArray();
    }
}
