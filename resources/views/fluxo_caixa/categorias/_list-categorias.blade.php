<div class="row border-bottom py-2 mb-2">
    <div class="col-4 col-sm-4 font-weight-bolder opacity-7 text-xs">Descriçao</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Tipo</div>
    <div class="col-4 col-sm-4 font-weight-bolder opacity-7 text-xs">Grupo</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Status</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach ($categorias as $categoria)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-4">{{ $categoria->descricao ?? '' }}</div>

    <div class="col-4 col-sm-2">
        @if($categoria->tipo == 'entrada')
            <span class="badge text-bg-primary">Entrada</span>
        @elseif($categoria->tipo == 'saida')
            <span class="badge text-bg-danger">Saida</span>
        @endif
    </div>
    <div class="col-4 col-sm-4">{{ $categoria->grupo->descricao ?? 'sem grupo' }}</div>

    <div class="col-4 col-sm-1">
        <label class="switch">
            <input type="checkbox" class="toggle-status" data-id="{{ $categoria->id }}" {{ $categoria->status == 'ativo' ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-4 col-sm-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item editar-categorias" href="{{route('app.fluxo-caixa.categorias.edit', ['id' => $categoria->id])}}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy2" href="{{ route('app.fluxo-caixa.categorias.destroy', ['id' => $categoria->id]) }}" data-id="{{ $categoria->id }}">
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
        {{ $categorias->links('pagination::bootstrap-4') }}
    </nav>
</div>