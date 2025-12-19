<div class="row d-flex border-bottom py-2 mb-2">
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Entidade</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">ID</div>
    <div class="col-4 col-sm-1 font-weight-bolder opacity-7 text-xs">Evento</div>
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">old_values</div>
    <div class="col-4 col-sm-3 font-weight-bolder opacity-7 text-xs">new_values</div>
    <div class="col-4 col-sm-2 font-weight-bolder opacity-7 text-xs">Colaborador</div>
   
</div>

@foreach($auditorias as $key => $value)
<div class="row border-bottom py-1" style="font-size: 14px;">
    <div class="col-4 col-sm-1">{{ isset($value->model) ? class_basename($value->model) : '' }}</div>
    <div class="col-4 col-sm-1">{{ $value->auditable_id ?? '' }}</div>
    <div class="col-4 col-sm-1">{{ $value->event ?? '' }}</div>
  <div class="col-4 col-sm-3">
    {{ isset($value->old_values) ? json_encode($value->old_values, JSON_PRETTY_PRINT) : '' }}
</div>
<div class="col-4 col-sm-3">
    {{ isset($value->new_values) ? json_encode($value->new_values, JSON_PRETTY_PRINT) : '' }}
</div>
    <div class="col-4 col-sm-2">{{ $value->colaborador->nome ?? '' }}</div>



</div>
@endforeach

<div id="pagination-links" class="mt-3 w-100">
    <nav class="d-flex flex-wrap justify-content-center">
        {{ $auditorias->links('pagination::bootstrap-4') }}
    </nav>
</div>