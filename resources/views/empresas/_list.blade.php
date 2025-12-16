
<div class="row d-none d-sm-flex border-bottom py-2 mb-2">
    <div class="col-3 col-md-4 font-weight-bolder opacity-7 text-xs">Nome</div>
    <div class="col-3 col-md-3 font-weight-bolder opacity-7 text-xs">Responsavel</div>
    <div class="col-2 col-md-2 font-weight-bolder opacity-7 text-xs">Telefone</div>
    <div class="col-2 col-md-2 font-weight-bolder opacity-7 text-xs">Status</div>
    <div class="col-2 col-md-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach($empresas as $key => $value)
<div class="row py-1" style="font-size: 14px;">
        <div class="col-3 col-md-4">{{ $value->nome ?? '' }}</div>
        <div class="col-3 col-md-3">{{ $value->nome_contato ?? '' }}</div>
        <div class="col-2 col-md-2 phoneMask">{{ $value->telefone ?? '' }}</div>
        <div class="col-2 col-md-2 ">
        <label class="switch">
            <input type="checkbox" class="toggle-status" data-id="{{ $value->id }}" {{ $value->status == 'ativo' ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>
        
        <div class="col-2 col-md-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item" href="{{ route('app.empresas.edit', $value->id) }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy" href="javascript:void(0);" data-id="{{ $value->id }}">
                        Excluir
                    </a>
                </li>
            </ul>
        </div>
    </div>
    </div>
@endforeach
<div id="pagination-links" class="mt-3">
    {{ $empresas->links('pagination::bootstrap-4') }}
</div>