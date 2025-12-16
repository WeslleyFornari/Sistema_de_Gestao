<div class="row mt-2 mx-2">

    <div class="form-group col-12 col-sm-4">
        <span class="titulo"> Cliente: </span><br>
        {{$pagamento->usuario->name ?? 'não informado'}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Grupo: </span><br>
        {{$pagamento->grupo->descricao ?? ''}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Categoria: </span><br>
        {{$pagamento->categoria->descricao ?? 'não disponivel'}}
    </div>

    <div class="form-group col-sm-4">
        <span class="titulo"> Transação: </span><br>
        <span class="text-center">{{$pagamento->transacao_key ?? 'não informado'}}</span>
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Gateway: </span><br>
        {{$pagamento->geteway->descricao ?? ''}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Valor: </span><br>
        R${{getMoney($pagamento->valor) ?? ''}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Forma Pagamento: </span><br>
        {{$pagamento->formaPagamento->tipo ?? ''}}
    </div>

    <div class="form-group col-sm-4">
        <span class="titulo"> Bandeira: </span><br>
        {{$pagamento->flag->nome ?? ''}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Taxa: </span><br>
        R${{getMoney($pagamento->taxa) ?? ''}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo"> Valor liquido: </span><br>
        R${{getMoney($pagamento->valor_liquido) ?? ''}}
    </div>
    <div class="form-group col-sm-4">
        <span class="titulo text-center"> Status: </span><br>
        @if($pagamento->status == 'pago')
        <span class="badge text-bg-success">{{$pagamento->status ?? ''}}</span>
        @elseif($pagamento->status == 'cancelado')
        <span class="badge text-bg-danger">{{$pagamento->status ?? ''}}</span>
        @elseif($pagamento->status == 'pendente')
        <span class="badge text-bg-warning">{{$pagamento->status ?? ''}}</span>
        @endif
    </div>
</div>

<div class="row mt-3 text-center">
    <h4>Detalhes do Pedido</h4>
</div>
<!-- Items -->
<div class="row border-bottom mt-2 mx-2 cabecalho">

    <div class="col-6 col-md-3">Pedido</div>
    <div class="col-6 col-md-3">Item</div>
    <div class="col-6 col-md-3">Quantidade</div>
    <div class="col-6 col-md-3">Preço</div>
</div>

@foreach($compras as $compra)
<div class="row border-bottom mt-2 mx-2">
    <div class="col-6 col-md-3">{{$pagamento->numero}}</div>
    <div class="col-6 col-md-3">{{ $compra->produto->descricao ?? ''}}</div>
    <div class="col-6 col-md-3 text-center">{{ $compra->qtd }}</div>
    <div class="col-6 col-md-3">R${{ $compra->valor }}</div>
  
</div>
@endforeach