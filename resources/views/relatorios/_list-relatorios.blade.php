<div class="row d-flex border-bottom py-2 mb-2">
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">Colaborador</div>
    <div class="col-4 col-sm-4 font-weight-bolder opacity-7 text-xs">Arquivo</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Data_Criação</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Status</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs text-center">Ações</div>
</div>

@foreach($relatorios as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-3">{{ $value->colaborador->nome ?? '' }}</div>
    <div class="col-4 col-sm-4">{{ $value->file_name ?? '' }}</div>
    <div class="col-4 col-sm-2">{{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '' }}</div>
    <div class="col-4 col-sm-2">
        @php
        $statusClasses = [
        'pending' => 'bg-warning text-dark',
        'processing' => 'bg-primary text-white',
        'completed' => 'bg-success text-white',
        'failed' => 'bg-danger text-white'
        ];
        $statusClass = $statusClasses[$value->status] ?? 'bg-secondary';
        @endphp
        <span class="badge {{ $statusClass }}" style="font-size: 11px;">
            {{ ucfirst($value->status) }}
        </span>
    </div>

    <div class="col-sm-1 d-flex justify-content-center">
        @if($value->status == 'completed' && $value->file_path)
        @php
    
        $extension = pathinfo($value->file_path, PATHINFO_EXTENSION);
        $icon = 'fa-file-download';
        $color = 'text-primary';

        if (in_array($extension, ['xls', 'xlsx', 'csv'])) {
        $icon = 'fa-file-excel';
        $color = 'text-success';
        } elseif ($extension == 'pdf') {
        $icon = 'fa-file-pdf';
        $color = 'text-danger';
        }
        @endphp

        <a href="{{ asset('storage/' . $value->file_path) }}"
            target="_blank"
            title="Baixar Arquivo"
            class="btn btn-link p-0">
            <i class="fas {{ $icon }} {{ $color }} fa-2x"></i>
        </a>
        @else
        <i class="fas fa-clock text-muted fa-2x opacity-5" title="Aguardando processamento"></i>
        @endif
    </div>
</div>
@endforeach

<div id="pagination-links" class="mt-3 w-100">
    <nav class="d-flex flex-wrap justify-content-center">
        {{ $relatorios->links('pagination::bootstrap-4') }}
    </nav>
</div>