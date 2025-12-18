@extends('layouts.app')

@section('title', 'Colaboradores')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
            <form action="{{route('app.colaboradores.update', ['id'=>$colaborador->id])}}" id="formUpdate">
                @csrf

                <div class="row mt-2">
                    <div class="form-group col-12 col-sm-4">
                        <span class="titulo"> Nome: *</span>
                        <input type="text" name="nome" id="nome" value="{{$colaborador->nome}}" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-4 ">
                        <span class="titulo"> Email: *</span>
                        <input type="text" name="email" id="email" value="{{$colaborador->email}}" class="form-control" readonly>
                    </div>

                    <div class="form-group col-12 col-sm-4 ">
                        <span class="titulo"> CPF: *</span>
                        <input type="text" name="cpf" id="cpf" value="{{$colaborador->cpf}}" class="form-control cpfMask" readonly>
                    </div>
                    @if(Auth::user()->role == 'admin')
                    <div class="form-group col-12 col-sm-4" style="margin-top:5px">
                        <span class="titulo"> Perfil: *</span>

                        <select class="form-select" id="roleSelect" name="role" required>
                            <option value="admin" @if($colaborador->user->role == 'admin' ?? '') selected @endif>Administrador</option>
                            <option value="user" @if($colaborador->user->role == 'user' ?? '') selected @endif >Colaborador</option>
                        </select>

                    </div>
                    @else
                    <div class="form-group col-12 col-sm-4" style="margin-top:5px">
                        <span class="titulo"> Perfil:</span>
                        {{-- Input desabilitado apenas para exibição visual --}}
                        <input type="text" class="form-control" value="{{ $colaborador->user->role == 'admin' ? 'Administrador' : 'Colaborador' }}" readonly disabled>
                        {{-- Input hidden para manter o valor no envio do formulário se necessário --}}
                        <input type="hidden" name="role" value="{{ $colaborador->user->role }}">
                    </div>
                    @endif

                    <div class="col-12 col-sm-4">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="password">
                    </div>

                    <div class="col-12 col-sm-4">
                        <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="password_confirmation">
                    </div>
                    <div class="col-12 col-sm-4" style="margin-top:30px;">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                    </div>
                </div>



            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')

<!-- Evento Role / Grupo -->
<script>
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
</script>
<script>
    // ATUALIZAR
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