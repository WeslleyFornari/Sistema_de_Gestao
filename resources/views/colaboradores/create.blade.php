@extends('layouts.app')

@section('title', 'Cadastro de Usuários')

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
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group col-12 col-sm-4 ">
                            <span class="titulo"> Email: *</span>
                            <input type="text" name="email" id="email" class="form-control" required>
                        </div>
                    @if(Auth::user()->role == 'master')
                        <div class="form-group col-12 col-sm-4" style="margin-top:0px">
                        <span class="titulo"> Empresa: *</span>
                            <select class="form-select" id="" name="empresa_id" required>   
                                <option value="">Selecione</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nome ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif(Auth::user()->role == 'admin')
                        <input type="hidden" name="empresa_id" value="{{Auth::user()->empresa_id}}">
                        <div class="form-group col-12 col-sm-4" style="margin-top:0px">
                        <span class="titulo"> Grupo: *</span>
                            <select class="form-select" id="grupoSelect" name="grupo_id" required>   
                                <option value="">Selecione</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->descricao ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="row mt-2">
                        <div class="form-group col-12 col-sm-4" style="margin-top:5px">
                            <span class="titulo"> Perfil: *</span>
                            @if(Auth::user()->role == 'master')
                                <select class="form-select" id="roleSelect" name="role" required>   
                                    <option value="">Selecione</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            @else
                            <select class="form-select" id="roleSelect" name="role" required>   
                                    <option value="">Selecione</option>
                                    <option value="admin">Administrador</option>
                                    <option value="grupo">Grupo</option>
                                </select>
                            @endif
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="senha" class="form-label" >Senha:</label>
                            <input type="password" class="form-control" id="senha" name="password">    
                        </div>
                    
                        <div class="col-12 col-sm-4">
                            <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                            <input type="password" class="form-control" id="confirmar_senha" name="password_confirmation">
                        </div>
                        <div class="col-12 col-sm-4 px-3" style="margin-top:30px;">
                            <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add +</button>
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
    // CADASTRAR
    $("body").on('submit', '#formStore', function(e) {

        e.preventDefault();
        let formData = new FormData($('#formStore')[0]);

        $("span.error").remove()

        $.ajax({
            url: "{{ route('app.usuarios.store') }}",
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

                    window.location.href = "{{route('app.usuarios.index')}}";

                });
            },
            error: function (err) {
                console.log(err);

                if (err.status == 422) { 
                    console.log(err.responseJSON);
                    $('#success_message').fadeIn().html(err.responseJSON.message);
                   
                    console.warn(err.responseJSON.errors);
                   
                    $.each(err.responseJSON.errors, function (i, error) {
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