@extends('layouts.app')

@section('title', 'Histórico da Compra')

@section('assets')
<style>
    table,
    th,
    td {
        border: 1px solid;
        border-collapse: collapse;
        border: 1px solid rgb(190, 188, 188);
    }

    table {
        border-radius: 10px;
        border: 1px solid rgb(190, 188, 188);
    }

    th {
        text-align: center;
        background: rgb(242, 240, 240);
        ;
    }

    td {
        padding-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Pedido - {{$pagamento->numero}}</h4>
            <h6><strong>Status: </strong>{{ ucfirst($pagamento->status) }}</h6>
            <a href="{{ route('app.pagamentos.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
        <div class="card-body">

            <div class="card my-3 shadow p-4">
                <h5 class="mb-3">Itens da compra</h5>

                <div class="row px-3">
                    <table>
                        <tr>
                            <th>Pedido</th>
                            <th>Item</th>
                            <th>Quant.</th>
                            <th>Preço</th>
                        </tr>
                        @foreach($compras as $compra)
                        <tr>
                            <td>{{$pagamento->numero}}</td>
                            <td>{{ $compra->produto->descricao }}</td>
                            <td style="text-align:center;">{{ $compra->qtd }}</td>
                            <td style="text-align:center;">{{ $compra->valor }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection