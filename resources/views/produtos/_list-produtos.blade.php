<div class="row border-bottom py-2 mb-2 ">
    <div class="col-4 col-sm-4 col-md-4 font-weight-bolder opacity-7 text-xs">Descriçao</div>
    <div class="col-4 col-sm-4 col-md-2 font-weight-bolder opacity-7 text-xs">Grupo</div>
    <div class="col-4 col-sm-4 col-md-2 font-weight-bolder opacity-7 text-xs">Categoria</div>
    <div class="col-4 col-sm-4 col-md-2 font-weight-bolder opacity-7 text-xs">Valor</div>
    <div class="col-4 col-sm-4 col-md-1 font-weight-bolder opacity-7 text-xs ellipsis">Status</div>
    <div class="col-4 col-sm-4 col-md-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach($produtos as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-4 col-md-4">{{ $value->descricao ?? '' }}</div>
    <div class="col-4 col-sm-4 col-md-2">{{ $value->grupo->descricao ?? '' }}</div>
    <div class="col-4 col-sm-4 col-md-2">{{ $value->categoria->descricao ?? '' }}</div>
    <div class="col-4 col-sm-4 col-md-2">R${{ getMoney($value->valor ?? '' )}}</div>

    <div class="col-4 col-sm-4 col-md-1">
        <label class="switch">
            <input type="checkbox" class="toggle-status" data-id="{{ $value->id }}" {{ $value->status == 'ativo' ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-4 col-sm-4 col-md-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item editar-produtos" href="{{ route('app.produtos.edit', $value->id) }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy" href="{{ route('app.produtos.destroy', $value->id) }}" data-id="{{ $value->id }}">
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
        {{ $produtos->links('pagination::bootstrap-4') }}
    </nav>
</div>
