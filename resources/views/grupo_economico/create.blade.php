@extends('layouts.app')

@section('title', 'Grupo Econômico')

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
                        <span class="titulo"> Nome: *</span>
                        <input type="text" name="nome" id="nome" class="form-control"
                            placeholder="digite o nome do grupo ...">
                    </div>

                    <div class="col-12 col-sm-4 px-3" style="margin-top:25px;">
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
            url: "{{ route('app.grupo_economico.store') }}",
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

                    window.location.href = "{{route('app.grupo_economico.index')}}";

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