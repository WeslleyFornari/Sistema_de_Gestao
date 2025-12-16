@extends('layouts.app')

@section('title', 'Cadastro de Categorias da Conta')

@section('content')
<div class="container-flex px-2 mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Cadastrar</h3>
        </div>
        <div class="card-body">
            <form id="formStore">
                @csrf
                <div class="row mt-2">

                    <div class="form-group col-12 col-sm-5 col-md-4">
                        <span class="titulo"> Descrição: *</span>
                        <input type="text" name="descricao" id="descricao" class="form-control" placeholder="Digite a descrição da conta" required>
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
                        <select name="tipo" id="" class="form-select" required>
                            <option value="">Selecione</option>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saida</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4 col-md-2" style="margin-top:26px;">
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add +</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // CADASTRAR
    $("body").on('submit', '#formStore', function(e) {

        e.preventDefault();
        let formData = new FormData($('#formStore')[0]);

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: "{{ route('app.fluxo-caixa.categorias.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                $("#formStore")[0].reset();

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
                        text: 'Erro ao cadastrar a categoria.',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }
        });
    });

</script>
@endsection