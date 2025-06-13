<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockExport implements FromCollection, WithHeadings, WithStyles, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Item::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Kategori',
            'Stok',
            'Status'
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->category->name ?? '-',
            $item->stock,
            $item->stock == 0 ? 'Dipinjam / Kosong' : 'Tersedia',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'f2f2f2']
            ]
        ]);

        $rowCount = Item::count() + 1;
        $sheet->getStyle('A2:D' . ($rowCount + 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ]
        ]);
    }
}
