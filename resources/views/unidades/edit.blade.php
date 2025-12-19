@extends('layouts.app')

@section('title', 'Grupo Econômico')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
            <form action="{{route('app.unidades.update', ['id'=>$unidade->id])}}" id="formUpdate">
                @csrf

                <div class="row mt-2">
                    <div class="form-group col-12 col-sm-4">
                        <span class="titulo"> Nome Fantasia: *</span>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" value="{{$unidade->nome_fantasia}}" class="form-control" required>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> Razão Social: *</span>
                        <input type="text" name="razao_social" id="razao_social" value="{{$unidade->razao_social}}" class="form-control">
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> CNPJ: *</span>
                        <input type="text" name="cnpj" id="cnpj" value="{{$unidade->cnpj}}" class="form-control cnpjMask">
                    </div>

                    <div class="form-group col-12 col-sm-4" style="margin-top:0px">
                        <span class="titulo"> Bandeiras: *</span>
                        <select class="form-select" id="bandeira_id" name="bandeira_id" required>
                            <option value="{{$unidade->bandeira->id ?? ''}}">{{$unidade->bandeira->nome ?? ''}}</option>
                            @foreach($bandeiras->where('id', '!=', optional($unidade->bandeira)->id) as $value)
                            <option value="{{ $value->id }}">{{ $value->nome ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-3" style="margin-top:25px;">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-fw fa-lg fa-check-circle me-2"></i>Atualizar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')

<script>
    $("body").on('submit', '#formUpdate', function(e) {

        e.preventDefault();
        let formData = new FormData($('#formUpdate')[0]);

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                $("#formUpdate")[0].reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {

                    window.location.href = "{{route('app.unidades.index')}}";

                });
            },
            error: function(err) {
                console.log(err);

                if (err.status == 422) {
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);

                    console.warn(err.responseJSON.errors);

                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<span class="error" style="color: red; font-size:12px; font-weight: bold; margin-left:10px; border: none;">' + error[0] +
                            '</span>'));
                    });
                }
            }
        });
    });
</script>
@endsection