<div class="row d-flex border-bottom py-2 mb-2">
    <div class="col-4 col-md-2 col-lg-2 col-xl-2 font-weight-bolder opacity-7 text-xs">Data</div>
    <div class="col-4 col-md-3 font-weight-bolder opacity-7 text-xs">Descrição/ Categoria</div>
    <div class="col-4 col-md-2 font-weight-bolder opacity-7 text-xs">Conta / Grupo</div>
    <div class="col-4 col-md-2 font-weight-bolder opacity-7 text-xs">Parcela</div>
    <div class="col-4 col-md-2 font-weight-bolder opacity-7 text-xs ps-4">Valor</div>
    <div class="col-4 col-md-1 font-weight-bolder opacity-7 text-xs">Pago</div>
</div>

@foreach ($lancamentos as $lancamento)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-md-2"> {{ \Carbon\Carbon::parse($lancamento->data_lancamento)->format('d/m/Y') }}</div>
    <div class="col-4 col-md-3">
        <strong>{{ $lancamento->descricao }} </strong><br>
        {{ $lancamento->categoria->descricao ?? 'Sem categoria' }}
    </div>
    <div class="col-4 col-md-2"> 
        {{ $lancamento->conta->descricao ?? 'Sem conta' }}<br>
        <strong>{{ $lancamento->grupo->descricao ?? ''}} </strong>
    </div>
    <div class="col-4 col-md-2"> {{ $lancamento->parcela }}</div>

    @if($lancamento->tipo == 'saida')
        <div class="col-4 col-md-2 text-danger"> - R$ {{ getMoney($lancamento->valor) }}</div>
    @else
        <div class="col-4 col-md-2 text-success"> + R$ {{ getMoney($lancamento->valor) }}</div>
    @endif
    <div class="col-4 col-md-1 text-center">
        <button class="btn btn-sm toggle-payment rounded-circle 
            {{ $lancamento->pago === 'sim' ? 'btn-success' : 'btn-danger' }}"
            data-id="{{ $lancamento->id }}"
            title="Alterar status de pagamento"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <i class="fa {{ $lancamento->pago === 'sim' ? 'fa-check' : 'fa-times' }}"></i>
        </button>
    </div>

</div>
@endforeach
 <!-- Rodapé -->
 <div class="footer bg-light text-dark font-weight-bold p-2 text-right mt-2 mb-4">
        <div class="row">
            <div class="col-12 col-sm-4 text-success text-sm-center">Total Entradas: R$ {{ number_format($totalEntradas, 2, ',', '.') }}</div>
            <div class="col-12 col-sm-4 text-danger text-sm-center">Total Saídas: R$ {{ number_format($totalSaidas, 2, ',', '.') }}</div>
            <div class="col-12 col-sm-4 text-sm-center">Diferença: R$ {{ number_format($diferenca, 2, ',', '.') }}</div>
        </div>
    </div>


<div id="pagination-links" class="mt-3 w-100">
    <nav class="d-flex flex-wrap justify-content-center">
        {{ $lancamentos->links('pagination::bootstrap-4') }}
    </nav>
</div>