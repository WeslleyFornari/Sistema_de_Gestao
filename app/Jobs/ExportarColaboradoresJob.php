<?php

namespace App\Jobs;

use App\Models\Report;
use App\Exports\ColaboradoresExport; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class ExportarColaboradoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filtros;
    protected $reportId;

    public function __construct($reportId, $filtros)
    {
        $this->filtros = $filtros;
        $this->reportId = $reportId;
    }

   public function handle()
{
    $report = Report::find($this->reportId);
    if (!$report) return;

    $report->update(['status' => 'processing']);

    try {
        $nomeArquivo = 'relatorio_colaboradores_' . now()->format('Ymd_His') . '.xlsx';
        $caminhoRelativo = 'relatorios/' . $nomeArquivo;

        // Salva o Excel (Verifique se sua classe Export aceita array ou collection)
      Excel::store(new ColaboradoresExport($this->filtros), $caminhoRelativo, 'public');

        $report->update([
            'file_name' => $nomeArquivo,
            'file_path' => $caminhoRelativo,
            'status' => 'completed'
        ]);

    } catch (\Exception $e) {
        if ($report) {
            $report->update(['status' => 'failed']);
        }
        \Log::error("ERRO NO JOB EXCEL: " . $e->getMessage());
    }
}
    
}
