@extends('layouts.app')

@section('title', 'Colaboradores')

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
                    <div class="form-group col-12 col-sm-4">
                        <span class="titulo"> Nome: *</span>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-4 ">
                        <span class="titulo"> Email: *</span>
                        <input type="text" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-4 ">
                        <span class="titulo"> CPF: *</span>
                        <input type="text" name="cpf" id="cpf" class="form-control cpfMask" required>
                    </div>

                    <div class="form-group col-12 col-sm-4" style="margin-top:0px">
                        <span class="titulo"> Unidade: *</span>
                        <select class="form-select" id="unidade_id" name="unidade_id" required>
                            <option value="">Selecione</option>
                            @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}">{{ $unidade->nome_fantasia ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="form-group col-12 col-sm-4" style="margin-top:5px">
                        <span class="titulo"> Perfil: *</span>
                        <select class="form-select" id="roleSelect" name="role" required>
                            <option value="">Selecione</option>
                            <option value="admin">Administrador</option>
                            <option value="user">Usuário</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-4 px-3" style="margin-top:30px;">
                        <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle me-2"></i>Adicionar</button>
                    </div>
               

                </div>

               
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<!-- Evento Role / Grupo -->
<!-- <script>
    function toggleGrupoSelect() {
        const roleSelect = document.getElementById('roleSelect');
        const grupoSelect = document.getElementById('grupoSelect');

        if (roleSelect.value === 'admin') {
            grupoSelect.setAttribute('disabled', true);
            grupoSelect.value = '';
        } else {
            grupoSelect.removeAttribute('disabled');
        }
    }

    document.addEventListener('DOMContentLoaded', toggleGrupoSelect);
    document.getElementById('roleSelect').addEventListener('change', toggleGrupoSelect);
</script> -->

<script>

    $("body").on('submit', '#formStore', function(e) {

        e.preventDefault();
        let formData = new FormData($('#formStore')[0]);

        $("span.error").remove()

        $.ajax({
            url: "{{ route('app.colaboradores.store') }}",
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

                    window.location.href = "{{route('app.colaboradores.index')}}";

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