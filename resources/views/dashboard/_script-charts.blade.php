<script type="text/javascript">
  google.charts.load('current', { packages: ['corechart'] });
  google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Dados vindos do controller
        var data = google.visualization.arrayToDataTable([
        ['Mês', 'Faturamento (R$)'],
        @foreach($faturamentoAnual as $item)
            ['{{ $item["label"] }}', {{ $item["y"] }}],
        @endforeach
        ]);

        // Formata para R$ com vírgula e 2 casas decimais
        var formatter = new google.visualization.NumberFormat({
        
        decimalSymbol: ',',
        groupingSymbol: '.',
        fractionDigits: 2
        });
        formatter.format(data, 1); // Coluna 1 é onde estão os valores

        var options = {
        title: 'Faturamento Anual {{ date("Y") }}',
        titleTextStyle: {
        
            fontSize: 16,
            bold: true,
            alignment: 'center'
        },
        legend: { position: 'none' },
        width: '100%',
        height: 300,
        chartArea: { width: '80%' },
        hAxis: {
            title: '',
            slantedText: true
        },
        vAxis: {
            title: 'Valor (R$)',
            format: 'decimal'
        },
        colors: ['#C0C0C0']
        };

    var chart = new google.visualization.ColumnChart(document.getElementById('chartAnual'));
    chart.draw(data, options);
  }
</script>


<script type="text/javascript">
    
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
            ['Dia', 'Valor (R$)'],
            @foreach($faturamentoDiario as $item)
                ['{{ $item['label'] }}', {{ $item['y'] }}],
            @endforeach
        ]);

        var formatter = new google.visualization.NumberFormat({
            prefix: 'R$ ',
            decimalSymbol: ',',
            groupingSymbol: '.',
            fractionDigits: 2
        });
        formatter.format(data, 1); // Aplica no eixo Y (coluna 1)
        
        var options = {
            width: '100%',
            legend: {
                position: 'none'
            },
            chart: {
                title: 'Faturamento Diário',

            },
            titleTextStyle: {
                fontSize: 16,
                bold: true,
                alignment: 'center'
            },
            axes: {
                x: {
                    0: {
                        side: 'top',
                        label: ''
                    }
                }
            },
            bar: {
                groupWidth: "90%"
            }
        };

        var chart = new google.charts.Bar(document.getElementById('chartDiario'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>

<!-- Pie Google -->
<script>
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var jsonData = <?php echo json_encode($graficoPagamentos); ?>;

        if (jsonData.length === 0) {
            document.getElementById('torta_id').innerHTML = '<p style="text-align:center;">Nenhum dado disponível</p>';
            return;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Forma de Pagamento');
        data.addColumn('number', 'Quantidade');
        data.addRows(jsonData);

        var options = {
            title: '      Forma de Pagamento Mais Usadas',
            sliceVisibilityThreshold: 0.2,
            titleTextStyle: {
                fontSize: 14,
                bold: true,
                alignment: 'center'
            },
            legend: {
                position: 'bottom',
                textStyle: {
                    fontSize: 12
                },
                alignment: 'center'
            }, // Centraliza e empurra um pouco para baixo
        };

        var chart = new google.visualization.PieChart(document.getElementById('torta_id'));
        chart.draw(data, options);
    }
</script>



<!-- Donut Google -->
<script>
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var jsonData = <?php echo json_encode($produtosMaisVendidos); ?>;
        var data = google.visualization.arrayToDataTable(
            jsonData
        );

        var options = {
            title: '     Produtos mais Vendidos',
            pieHole: 0.4,
            chartArea: {
                width: '70%',
                height: '60%',
                top: 55
            },

            titleTextStyle: {
                fontSize: 14,
                bold: true,
                alignment: 'center'
            }, // Aumenta o tamanho e centraliza
            titlePosition: 'center', // Centraliza o título
            legend: {
                position: 'right',
                textStyle: {
                    fontSize: 12, // Aumenta o tamanho do texto da legenda            
                }
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>

<!-- Bar Forma PAgto -->
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChartFormaPagamento);

  function drawChartFormaPagamento() {

        var data = google.visualization.arrayToDataTable([
            
        ['Forma de Pagamento', 'Valor (R$)', { role: 'style' }],
        @foreach($formaPagamento as $item)
            ['{{ $item['label'] }}', {{ $item['y'] }}, '#014D65'],
        @endforeach
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([
      0, 
      1,
      {
        calc: function(dt, row) {
          return dt.getValue(row, 1).toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
          });
        },
        type: "string",
        role: "annotation"
      },
      2
    ]);

        var options = {
        title: "Faturamento x Forma de Pagamento",
        titleTextStyle: {
            fontSize: 16,
            bold: true,
            color: '#000'
        },
        bar: { groupWidth: "90%" },
        legend: { position: "none" },
        chartArea: { width: '85%', height: '70%' },
        vAxis: {
            title: 'Valor (R$)',
            format: 'decimal'
        }
        };

    var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
    chart.draw(view, options);
  }
</script>

<script>

        var jsonData1 = <?php echo json_encode($formaPagamento); ?>;
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Faturamento x Forma de Pagamento",
                fontSize: 16,
                fontFamily: "Arial",
                fontWeight: "bold"
            },
            axisX: {
                interval: 1
            },
            axisY2: {
                interlacedColor: "rgba(1,77,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "R$ - Reais",
                labelFormatter: function(e) {
                    // Formata os valores do eixo Y
                    return e.value.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            data: [{
                type: "bar",
                name: "companies",
                color: "#014D65",
                axisYType: "secondary",
                dataPoints: jsonData1
            }],
            toolTip: {
                contentFormatter: function(e) {
                    // Formata apenas o valor no tooltip
                    var valor = e.entries[0].dataPoint.y;
                    var valorFormatado = valor.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    return e.entries[0].dataPoint.label + ": " + valorFormatado;
                }
            }
        });
        chart.render();
</script>