<?php

namespace App\Jobs;

use App\Models\Colaborador;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Exception;

class ExportarColaboradoresPdfJob implements ShouldQueue
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

            $export = new \App\Exports\ColaboradoresExport($this->filtros);
            $colaboradores = $export->query()->get();

            $nomeArquivo = 'relatorio_colaboradores_' . now()->format('Ymd_His') . '.pdf';
            $caminhoRelativo = 'relatorios/' . $nomeArquivo;

            $pdf = Pdf::loadView('colaboradores.report', [
                'colaboradores' => $colaboradores
            ]);

            // 4. Salvamos o arquivo final no storage
            Storage::disk('public')->put($caminhoRelativo, $pdf->output());

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
