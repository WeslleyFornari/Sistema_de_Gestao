<?php

namespace App\Exports;

use App\Models\Pagamentos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PagamentoExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithColumnFormatting
// , WithMapping, WithColumnFormatting
{

    public function styles(Worksheet $sheet)
    {

        return [

            1 => ["font" => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [

            'Ordem',
            'Cliente',
            'Grupo',
            'Categoria',
            'Transation',
            'id_gateway',
            'Valor Total',
            'Forma Pagamento',
            'Bandeira',
            'Taxa',
            'Liquido',
            'Data da Compra',
            'Status'
        ];
    }

    public function collection()
    {
        return Pagamentos::with(['usuario', 'grupo', 'categoria', 'formaPagamento', 'flag'])->get();

    }

    public function map($pagamento): array
    {
        return  [
            $pagamento->numero ?? 'numero não encontrado',
            $pagamento->usuario->name ?? 'Usuario não encontrado',
            $pagamento->grupo->descricao ?? 'Grupo não encontrado',
            $pagamento->categoria->descricao ?? 'Categoria não encontrada',
            $pagamento->transacao_key ?? 'Transação inexistente',
            $pagamento->id_geteway ?? 'gateway não encontrado',
            $pagamento->valor ?? 'sem valor',
            $pagamento->formaPagamento->tipo ?? 'forma não encontrada',
            $pagamento->flag->nome ?? 'Bandeira não encontrada',
            $pagamento->taxa ?? 'taxa não encontrada',
            $pagamento->valor_liquido ?? 'sem valor',
            Date::dateTimeToExcel($pagamento->created_at),
            $pagamento->status,

        ];
    }

    public function columnFormats(): array
    {
        return [

            "L" => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
