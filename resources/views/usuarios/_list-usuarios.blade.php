<div class="row d-flex border-bottom py-2 mb-2">
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">Nome</div>
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">Email</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Perfil</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Grupo</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs ellipsis">Status</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach($usuarios as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-3">{{ $value->name ?? '' }}</div>
    <div class="col-4 col-sm-3">{{ $value->email ?? '' }}</div>
    <div class="col-4 col-sm-2">{{ $value->role ?? '' }}</div>
    <div class="col-4 col-sm-2">{{ $value->grupo->descricao ?? '' }}</div>
    <div class="col-4 col-sm-1">
        <label class="switch">
            <input type="checkbox" class="toggle-status" data-id="{{ $value->id }}" {{ $value->status == 'ativo' ? 'checked' : '' }}>
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
                    <a class="dropdown-item editar-usuarios" href="{{ route('app.usuarios.edit', $value->id) }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy" href="{{ route('app.usuarios.destroy', $value->id) }}" data-id="{{ $value->id }}">
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
        {{ $usuarios->links('pagination::bootstrap-4') }}
    </nav>
</div>