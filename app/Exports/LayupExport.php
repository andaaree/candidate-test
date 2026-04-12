<?php

namespace App\Exports;

use App\Models\Layup;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LayupExport implements FromCollection, WithHeadings, WithMapping
{
    protected $supplierId;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function collection()
    {
        return Supplier::where('id', $this->supplierId)->with('layups.layers')->get();
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
        foreach ($supplier->layups as $layup) {
            if (count($supplier->layups) > 0) {
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
                        $layer->layer_order,
                        'N/A',
                        'N/A',
                        'N/A',
                    ];
                }
            }
            else{
                $rows[] = [
                    $supplier->name,
                    'No Layup',
                    'N/A',
                    'N/A',
                    'N/A',
                ];
            }
        }
        return collect($rows)->toArray();
    }
}
