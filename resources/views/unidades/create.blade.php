@extends('layouts.app')

@section('title', 'Unidades')

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
                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> Nome Fantasia: *</span>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control"
                            placeholder="digite o nome ...">
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> Razão Social: *</span>
                        <input type="text" name="razao_social" id="razao_social" class="form-control"
                            placeholder="digite a razão social ...">
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> CNPJ: *</span>
                        <input type="text" name="cnpj" id="cnpj" class="form-control cnpjMask">
                    </div>

                     <div class="form-group col-12 col-sm-6" style="margin-top:0px">
                        <span class="titulo"> Bandeira: *</span>
                            <select class="form-select" id="bandeira_id" name="bandeira_id" required>   
                                <option value="">Selecione</option>
                                @foreach($bandeiras as $bandeira)
                                    <option value="{{ $bandeira->id }}">{{ $bandeira->nome ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>

                    <div class="col-12 col-sm-3 px-3" style="margin-top:25px;">
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle me-2"></i>Adicionar</button>
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

        $("span.error").remove()

        $.ajax({
            url: "{{ route('app.unidades.store') }}",
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