<?php

namespace App\Exports;

use App\Models\Colaborador;
use Maatwebsite\Excel\Concerns\FromQuery; // Alterado de FromCollection para FromQuery
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ColaboradoresExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithColumnFormatting
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'E-mail',
            'CPF',
            'Unidade',
        ];
    }

    public function map($colaborador): array
    {
        return [
            $colaborador->id,
            $colaborador->nome,
            $colaborador->email,
            $colaborador->cpf,
            $colaborador->unidade ? $colaborador->unidade->nome_fantasia : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, 
        ];
    }
}