@extends('layouts.app')
@section('title','Empresas')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex pb-0">
                    <div class="col-6">
                        <h5>Cadastro</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formStore" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-5">
                                <label for="">Nome * </label>
                                <input type="text" name="nome" required class="form-control">
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="">Contato *</label>
                                <input type="text" name="nome_contato" required class="form-control">
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="">Telefone *</label>
                                <input type="text" name="telefone" required class="form-control phoneMask">
                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="">CNPJ *</label>
                                <input type="text" name="cnpj" required class="form-control cnpjMask">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <label for="">E-mail *</label>
                                <input type="text" name="email" required class="form-control">
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="border-bottom">Endereço</h6>

                            </div>
                            <div class="col-12 col-sm-5 col-md-4">
                                <label for="">CEP *</label>
                                <div class="input-group ">
                                    <input type="text" class="form-control cepMask border-radius-bottom-end-0" required name="cep" id="buscaCep">
                                    <button class="btn btn-outline-primary mb-0" type="button"> <i class="fa fa-search"></i></button>
                                </div>


                            </div>
                            <div class="col-12 col-sm-7 col-md-6">
                                <label for="">Endereço *</label>
                                <input type="text" name="endereco" required class="form-control">
                            </div>
                            <div class="col-3 col-sm-3 col-md-2">
                                <label for="">Número *</label>
                                <input type="text" name="numero" required class="form-control">
                            </div>
                            <div class="col-9 col-sm-9 col-md-4">
                                <label for="">Complemento</label>
                                <input type="text" name="complemento" class="form-control">
                            </div>
                            <div class="col-12 col-sm-7 col-md-4">
                                <label for="">Cidade *</label>
                                <input type="text" name="cidade" required class="form-control">
                            </div>
                            <div class="col-12 col-sm-5 col-md-4">
                                <label for="">Estado * </label>
                                <input type="text" name="estado" required class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3 border-top pt-5 mt-5">
                            <div class="col-6">
                                <a href="{{route('app.empresas.index')}}" class="btn btn-primary">Voltar</a>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-success" type="submit">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
            url: "{{ route('app.empresas.store') }}",
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

                    window.location.href = '{{route("app.empresas.index")}}';
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
                        text: 'Erro ao cadastrar a empresa.',
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