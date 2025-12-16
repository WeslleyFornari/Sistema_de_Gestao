@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
        <form action="{{route('app.fluxo-caixa.contas.update', ['id'=>$conta->id])}}" id="formUpdate">
                @csrf
                <div class="row mt-2">

                <div class="form-group col-12 col-sm-6 col-md-4">
                        <span class="titulo"> Descriçao: *</span>
                        <input type="text" name="descricao" id="descricao" value="{{$conta->descricao}}" class="form-control" required>
                    </div>
                    <div class="form-group col-12 col-sm-6 col-md-5">
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
                    <div class="form-group col-12 col-sm-4  col-md-3">
                        <span class="titulo"> Valor Inicial: *</span>
                        <input type="text" class="form-control moneyMask" value="{{ getMoney($conta->saldo_inicial) }}"id="saldo_inicial" name="saldo_inicial" required>
                    </div>

                    <div class="col-12 col-sm-3 col-md-3" style="margin-top:26px;">
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

                        window.location.href = "{{ route('app.fluxo-caixa.contas.index') }}";

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
                            text: 'Erro ao atualizar a conta.',
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