<div class="row d-none d-sm-flex border-bottom py-2 mb-2">
    <div class="col-6 col-sm-8 col-md-8 col-lg-8 col-xl-5 font-weight-bolder opacity-7 text-xs">Descriçao</div>
    <div class="col-3 col-sm-2 col-md-2 col-lg-2 col-xl-2 font-weight-bolder opacity-7 text-xs">Status</div>
    <div class="col-3 col-sm-2 col-md-2 col-lg-2 col-xl-1 font-weight-bolder opacity-7 text-xs">Ações</div>
</div>

@foreach($grupos as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">

    <div class="col-6 col-sm-8 col-md-8 col-lg-8 col-xl-5">{{ $value->descricao ?? '' }}</div>

    <div class="col-3 col-sm-2 col-md-2 col-lg-2 col-xl-2">
        <label class="switch">
            <input type="checkbox" class="toggle-status" data-id="{{ $value->id }}" {{ $value->status == 'ativo' ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-3 col-sm-2 col-md-2 col-lg-2 col-xl-1">
        <div class="dropdown">
            <a href="javascript:;" class="btn btn-light btn-icon btn-icon-only btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item editar-grupos" href="{{ route('app.grupos.edit', $value->id) }}">
                        Editar
                    </a>
                </li>
                <li>
                    <a class="dropdown-item btn-destroy" href="{{ route('app.grupos.destroy', $value->id) }}" data-id="{{ $value->id }}">
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
   
   
   
   
            
    
          


