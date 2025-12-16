<div class="row">
    @include('dashboard.include._begin-pedidos')
</div>


<div class="row">

<!-- DIario -->
    <div class="col-12 mt-4 mx-auto">
        <div class="card">
            <div class="card-body d-flex justify-content-center">
                <div id="chartDiario" class="w-100" style=" max-width: 100%; height: 300px;"></div>
            </div>
        </div>
    </div>


<!-- PIE -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4 mx-auto">
        <div class="card">
            <div class="card-body p-2 justify-content-center">
                <div class="justify-content-center">
                    <div id="torta_id" class="w-100" style="max-width: 100%; height: 300px;"></div>
                </div>
                <div class="row">
                    @foreach ($quantidadePagamentos as $pagamento)
                    <div class="col-4 {{ strtolower($pagamento['tipo']) }}">
                        <strong> {{ ucfirst($pagamento['tipo']) }}:</strong>
                        <span> R$ {{ number_format($pagamento['total_valor'], 2, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
<!-- Donnut -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4 mx-auto">
        <div class="card">
            <div class="card-body p-2 justify-content-center">

                <div id="donutchart" class="w-100" style="max-width: 100%; height: 320px;"></div>

            </div>
        </div>
    </div>

<!-- Bar Forma Pagto -->
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-4 p-3 mx-auto">
        <div class="card">
        <div class="card-body d-flex justify-content-center p-2">
        <div id="barchart_values" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>

<!-- Coluna Anual -->
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-4 p-3 mx-auto">
        <div class="card">
            <div class="card-body d-flex justify-content-center p-3">
                <div id="chartAnual" class="w-100" style="width: 100%; height: 350px;"></div>
            </div>
        </div>
    </div>




</div>