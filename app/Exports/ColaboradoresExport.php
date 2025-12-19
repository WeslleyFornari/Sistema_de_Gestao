<?php

namespace App\Exports;

use App\Models\Colaborador;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ColaboradoresExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithColumnFormatting
{
    protected $filtros;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

   public function query()
{
    $query = Colaborador::with('unidade');
    $f = $this->filtros; // Facilitador

    // data_get substitui o filled() para arrays comuns
    if (!empty(data_get($f, 'nome'))) {
        $query->where('nome', 'like', '%' . data_get($f, 'nome') . '%');
    }

    if (!empty(data_get($f, 'email'))) {
        $query->where('email', 'like', '%' . data_get($f, 'email') . '%');
    }

    if (!empty(data_get($f, 'cpf'))) {
        $query->where('cpf', 'like', '%' . data_get($f, 'cpf') . '%');
    }

    // Filtros de Hierarquia
    if (!empty(data_get($f, 'unidade_id'))) {
        $query->where('unidade_id', data_get($f, 'unidade_id'));
    } 
    elseif (!empty(data_get($f, 'bandeira_id'))) {
        $query->whereHas('unidade', function ($q) use ($f) {
            $q->where('bandeira_id', data_get($f, 'bandeira_id'));
        });
    } 
    elseif (!empty(data_get($f, 'grupo_id'))) {
        $query->whereHas('unidade.bandeira', function ($q) use ($f) {
            $q->where('grupo_economico_id', data_get($f, 'grupo_id'));
        });
    }

    return $query->orderBy('nome', 'asc');
}

    // 2. O MAP (Como cada linha deve ser escrita)
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

    // 3. O CABEÇALHO
    public function headings(): array
    {
        return ['ID', 'Nome', 'Email', 'CPF', 'Unidade'];
    }

    // 4. OS ESTILOS
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    // 5. A FORMATAÇÃO DE COLUNAS
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
