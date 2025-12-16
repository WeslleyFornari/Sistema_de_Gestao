@extends('layouts.app')

@section('title', 'Cadastro de Categorias')

@section('content')
<div class="container-fluid mt-5">
    <div class="card w-100">
        <div class="card-header">
            <h3>Cadastrar</h3>
        </div>
        <div class="card-body">
            <form id="formStore">
                @csrf
                <div class="row mt-2">

                    <div class="form-group col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                        <span class="titulo"> Descrição: *</span>
                        <input type="text" name="descricao" id="descricao" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-4 col-md-4 col-lg-5 col-xl-5">
                        <span class="titulo"> Grupo: *</span>
                    
                            <select class="form-select" id="grupoSelect" name="grupo">
                                <option value="">Selecione</option>
                                @foreach($grupos as $grupo)
                                <option value="{{$grupo->id}}">{{ $grupo->descricao}}</option>
                                @endforeach
                            </select>
                    </div>

                    <div class="col-12 col-sm-3 col-md-3 col-lg-2 col-xl-2 mt-4">
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
            url: "{{ route('app.categorias.store') }}",
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

                    window.location.href = "{{ route('app.categorias.index') }}";

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