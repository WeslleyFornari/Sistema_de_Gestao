@extends('layouts.app')

@section('title', 'Cadastro de Grupos')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Cadastrar</h3>
        </div>
        <div class="card-body">
            <form id="formStore">
                @csrf
                <div class="row mt-2">

                    <div class="form-group col-sm-9">
                        <span class="titulo"> Descrição: *</span>
                        <input type="text" name="descricao" id="descricao" class="form-control" required>
                    </div>

                    <div class="col-sm-3 mt-4">
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
            url: "{{ route('app.grupos.store') }}",
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

                    window.location.href = "{{ route('app.grupos.index') }}";

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
                        text: 'Erro ao cadastrar o grupo.',
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