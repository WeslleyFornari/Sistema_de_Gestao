<div class="row border-bottom py-2 mb-2 cabecalho">
    <div class="col-4 col-sm-2">Numero</div>
    <div class="col-4 col-sm-2">Cliente</div>
    <div class="d-none d-sm-block col-sm-1 text-center ellipsis">Produtos</div>
    <div class="col-4 col-sm-2">Valor</div>
    <div class="col-4 col-sm-2">Grupo</div>
    <div class="col-4 col-sm-2 text-center ellipsis">Status</div>
    <div class="col-4 col-sm-1 ellipsis">Ações</div>
</div>

@foreach($pagamentos as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-2">{{ $value->numero ?? '' }}</div>
    <div class="col-4 col-sm-2">{{ $value->usuario->name ?? 'não informado' }}</div>

    <div class="d-none d-sm-block col-sm-1  text-center">

            {{$value->produtos->count() ?? ''}}
    </div>

    <div class="col-4 col-sm-2">R${{ getMoney($value->valor ?? '')}}</div>
    <div class="col-4 col-sm-2">{{ $value->grupo->descricao ?? '' }}</div>

    <div class="col-4 col-sm-2 text-center"> 
        @if($value->status == 'pago')
        <span class="badge text-bg-success">{{ $value->status ?? '' }}</span>
        @elseif($value->status == 'cancelado')
        <span class="badge text-bg-danger">{{ $value->status ?? '' }}</span>
        @elseif($value->status == 'pendente')
        <span class="badge text-bg-warning">{{ $value->status ?? '' }}</span>
        @endif
    </div>

    <div class="col-4 col-sm-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item showPagamentos" href="{{ route('app.pagamentos.visualizar', $value->id) }}">
                        Ver
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy2" href="{{ route('app.pagamentos.destroy', $value->id) }}" data-id="{{ $value->id }}">
                        Excluir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endforeach

<div id="pagination-links" class="mt-3 w-100">
    <nav class="d-flex flex-wrap justify-content-center">
        {{ $pagamentos->links('pagination::bootstrap-4') }}
    </nav>
</div>