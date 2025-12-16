<div class="row border-bottom py-2 mb-2">
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Data</div>
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">Descrição / Categoria</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Conta / Grupo</div>
    <div class="d-none d-sm-flex col-sm-1 font-weight-bolder opacity-7 text-xs">Parcela</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Valor</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Pago</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach ($contas_receber as $receber)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-2"> {{ \Carbon\Carbon::parse($receber->data_lancamento)->format('d/m/Y') }}</div>
    <div class="col-4 col-sm-3">
        <strong>{{ $receber->descricao }} </strong><br>
        {{ $receber->categoria->descricao ?? 'Sem categoria' }}
    </div>
    <div class="col-4 col-sm-2"> 
        {{ $receber->conta->descricao ?? 'Sem conta' }}<br>
        <strong>{{ $receber->grupo->descricao ?? ''}} </strong>
    </div>
    <div class="d-none d-sm-block col-sm-1"> {{ $receber->parcela }}</div>
    <div class="col-4 col-sm-2 text-success"> + R$ {{ getMoney($receber->valor) }}</div>

    <div class="col-4 col-sm-1 text-center">
        <button class="btn btn-sm toggle-payment rounded-circle 
            {{ $receber->pago === 'sim' ? 'btn-success' : 'btn-danger' }}"
            data-id="{{ $receber->id }}"
            title="Alterar status de pagamento"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <i class="fa {{ $receber->pago === 'sim' ? 'fa-check' : 'fa-times' }}"></i>
        </button>
    </div>

    <div class="col-4 col-sm-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item btnEditItem" href="{{route('app.fluxo-caixa.contas-receber.edit', ['id' => $receber->id])}}" data-id="{{ $receber->id }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy2" href="{{ route('app.fluxo-caixa.contas-receber.destroy', ['id' => $receber->id]) }}" data-id="{{ $receber->id }}">
                        Excluir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endforeach
<div class="row bg-light text-dark font-weight-bold p-1 text-right mt-2 mb-4">
        <div class="col-sm-5"></div>
        <div class="col-6 col-sm-3">Total Entradas:</div>
        <div class="col-6 col-sm-3 text-success">R$ {{ number_format($totalEntradas, 2, ',', '.') }}</div>
</div>

<div id="pagination-links" class="mt-3 w-100">
    <nav class="d-flex flex-wrap justify-content-center">
        {{ $contas_receber->links('pagination::bootstrap-4') }}
    </nav>
</div>