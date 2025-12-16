@extends('layouts.app')

@section('title', 'Editar Categoria da Conta')

@section('content')
<div class="container-flex px-2 mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
        <form action="{{route('app.fluxo-caixa.categorias.update', ['id'=>$categoria->id])}}" id="formUpdate">
                @csrf
                <div class="row mt-2">

                <div class="form-group col-12 col-sm-5 col-md-4">
                        <span class="titulo"> Descriçao: *</span>
                        <input type="text" name="descricao" id="descricao" value="{{$categoria->descricao}}" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-4 col-md-3">
                      <span class="titulo"> Grupo: *</span>
                        <select name="grupo_id" id="grupo_id" class="form-select">
                        @if(Auth::user()->role != 'grupo')
                            <option value="">Sem Grupo</option>
                        @endif
                        @foreach($grupos as $value)
                            <option value="{{$value->id}}">{{$value->descricao}}</option>
                        @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group col-12 col-sm-3 col-md-3">
                    <span class="titulo"> Tipo: *</span>
                    <select name="tipo" id="" class="form-select">
                        <option value="">Selecione</option>
                        <option value="entrada" @if($categoria->tipo == 'entrada') selected @endif>Entrada</option>
                        <option value="saida" @if($categoria->tipo == 'saida') selected @endif>Saida</option>
                    </select>
                    </div>

                    <div class="col-12 col-sm-4 col-md-2" style="margin-top:26px;">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>
    $(document).ready(function() {

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

                        window.location.href = "{{ route('app.fluxo-caixa.categorias.index') }}";

                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            $('#' + key + 'Error').text(errors[key][0]);
                            $('#' + key).addClass('is-invalid');
                            if (key.includes('media')) {
                                $('#' + key + 'Error').show();
                                $('input[name="' + key + '"]').addClass('is-invalid');
                            }
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Erro ao atualizar a categoria.',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });
    });
</script>
@endsection