<div class="row d-flex border-bottom py-2 mb-2">
    <div class="col-4 col-sm-5 font-weight-bolder opacity-7 text-xs">Nome</div>
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">Data_Criaçao</div>
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">Data_Alteração</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach($grupos as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-5">{{ $value->nome ?? '' }}</div>
    <div class="col-4 col-sm-3">{{ $value->created_at->format('d/m/Y H:i:s') ?? '' }}</div>
    <div class="col-4 col-sm-3">{{ $value->updated_at->format('d/m/Y H:i:s') ?? '' }}</div>
   
    <div class="col-4 col-sm-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item editar-usuarios" href="{{ route('app.grupo_economico.edit', $value->id) }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy" href="{{ route('app.grupo_economico.destroy', $value->id) }}" data-id="{{ $value->id }}">
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
        {{ $grupos->links('pagination::bootstrap-4') }}
    </nav>
</div>