<div class="row d-flex d-sm-flex border-bottom py-2 mb-2">
    <div class="col-6 col-sm-4 col-md-5 col-lg-5 col-xl-5 font-weight-bolder opacity-7 text-xs">Descriçao</div>
    <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 font-weight-bolder opacity-7 text-xs">Grupo</div>
    <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2 font-weight-bolder opacity-7 text-xs">Status</div>
    <div class="col-6 col-sm-2 col-md-1 col-lg-1 col-xl-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach($categorias as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-6 col-sm-4 col-md-5 col-lg-5 col-xl-5">{{ $value->descricao ?? '' }}</div>
    <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">{{ $value->grupo->descricao ?? '' }}</div>

    <div class="col-6 col-sm-2 col-md-2 col-lg-2 col-xl-2">
        <label class="switch">
            <input type="checkbox" class="toggle-status" data-id="{{ $value->id }}" {{ $value->status == 'ativo' ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-6 col-sm-2 col-md-1 col-lg-1 col-xl-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item editar-categorias" href="{{ route('app.categorias.edit', $value->id) }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy" href="{{ route('app.categorias.destroy', $value->id) }}" data-id="{{ $value->id }}">
                        Excluir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endforeach
<div id="pagination-links" class="mt-3">
    {{ $categorias->links('pagination::bootstrap-4') }}
</div>