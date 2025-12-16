<form id="formLancamento" method="POST" action="{{ @$receber ? route('app.fluxo-caixa.contas-receber.update', $receber->id) : route('app.fluxo-caixa.contas-receber.store') }}">
    @csrf
    @if (@$receber)
        @method('PUT')
    @endif

    <!-- Descrição -->
    <div class="col-12 mb-3">
        <label for="descricao" class="form-label">Descrição *</label>
        <input type="text" class="form-control" id="descricao" name="descricao" value="{{ @$receber->descricao ?? '' }}" required>
    </div>

    <!-- Valor -->
    <div class="col-12 mb-3">
        <label for="valor" class="form-label">Valor *</label>               
        <input type="text" class="form-control moneyMask" id="valor" name="valor" value="{{ getMoney(@$receber->valor) }}" required>
    </div>

    <!-- Conta -->
    <div class="col-12 mb-3">
        <label for="conta" class="form-label">Conta *</label>
        <select id="conta" name="conta_id" class="form-control select2" required>
            <option value="">Selecione</option>
            @foreach($contas as $conta)
                <option value="{{ $conta->id }}" {{ @$receber->conta_id == $conta->id ? 'selected' : '' }}>{{ $conta->descricao }}</option>
            @endforeach
        </select>
    </div>
     <!-- Grupo -->
    <div class="col-12 mb-3">
        <label for="categoria" class="form-label">Grupo *</label>
        <select name="grupo_id" id="grupo_id" class="form-select">
        @if(Auth::user()->role != 'grupo')
            <option value="">Sem Grupo</option>
        @endif
        @foreach($grupos as $value)
            <option value="{{$value->id}}">{{$value->descricao}}</option>
        @endforeach
        </select>
    </div>
    <!-- Categoria -->
    <div class="col-12 mb-3">
        <label for="categoria" class="form-label">Categoria *</label>
        <select id="categoria" name="categoria_id" class="form-control select2" required>
            <option value="">Selecione</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ @$receber->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->descricao }}</option>
            @endforeach
        </select>
    </div>

    <!-- Pago -->
    <div class="col-12 mb-3">
        <label for="pago" class="form-label">Pago *</label>
        <select id="pago" name="pago" class="form-control" required>
            <option value="sim" {{ @$receber->pago == 'sim' ? 'selected' : '' }}>Sim</option>
            <option value="nao" {{ @$receber->pago == 'nao' ? 'selected' : '' }}>Não</option>
        </select>
    </div>

    <!-- Data de Lançamento -->
    <div class="col-12 mb-3">
        <label for="data_receber" class="form-label">Data de Lançamento *</label>
        <input type="text" class="form-control datepicker" id="data_lancamento" name="data_lancamento"  value="{{ @$receber && $receber->data_lancamento ? $receber->data_lancamento->format('Y-m-d') : '' }}" required>
    </div>

    <!-- Data de Pagamento -->
    <div class="col-12 mb-3">
        <label for="data_pagamento" class="form-label">Data de Pagamento</label>
        <input type="text" class="form-control datepicker" id="data_pagamento" name="data_pagamento" value="{{ @$receber && $receber->data_pagamento ? $receber->data_pagamento->format('Y-m-d') : ''  }}">
    </div>

    <!-- Parcela -->
    <div class="col-12 mb-3">
        <label for="parcela" class="form-label">Parcela</label>
        <input type="text" class="form-control" id="parcela" name="parcela" value="{{ @$receber->parcela ?? '' }}"  @if(!empty($receber->parcela)) disabled @endif>
    </div>
    <div class="col-12 mb-3">
        <!-- Tipo -->
        <input type="hidden" name="tipo" value="entrada">
        <!-- Botão de Salvar -->
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</form>
