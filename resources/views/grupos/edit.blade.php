@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
            <form action="{{route('app.grupos.update', ['id'=>$grupo->id])}}" method="POST" id="formUpdate">
                @csrf
                <div class="row mt-2">

                    <div class="form-group col-sm-9">
                        <span class="titulo"> Descriçao: *</span>
                        <input type="text" name="descricao" id="descricao" value="{{$grupo->descricao}}" class="form-control" required>
                    </div>

                    <div class="col-sm-3 mt-4">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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