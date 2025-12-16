@extends('layouts.app')

@section('content')
<style>
    .pix,
    .credito,
    .debito {
        font-size: 12px;
        text-align: center;
        color:black
    }

    .tipo {
        font-weight: bold;
    }

    .valor {
        font-weight: normal;
    }
</style>

<div class="py-4">
    <div class="row">
        <div class="col-12">

            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <!-- TAB Menu-->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Gráficos</button>
                        </li>
                    </ul>
                    <!-- TAB Conteudo-->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">@include('dashboard.include._grafico-pedidos')</div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<script>
 
 

</script>

@include('dashboard._script-charts')

@endsection