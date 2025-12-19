<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Colaboradores</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Listagem de Colaboradores</h2>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                {{-- Adicione outras colunas --}}
            </tr>
        </thead>
        <tbody>
            @foreach($colaboradores as $c)
                <tr>
                    <td>{{ $c->nome }}</td>
                    <td>{{ $c->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>