<!DOCTYPE html>
<html>
<head>
    <title>Reporte de ventas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1C91EC;
            color: white;
        }
        .chart-container {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Reporte Detallado</h1>

    <!-- Tabla -->
    <h2>Relación Completa de Stock</h2>
    <table>
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Fecha</th>
                <th scope="col">Boleta</th>
                <th scope="col">Cliente</th>
                <th scope="col">RUC/DNI</th>
                <th scope="col">Producto</th>
                <th scope="col">Código</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Importe S/.</th>
                <th scope="col">IGV S/.</th>
                <th scope="col">Ganancia S/.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $producto)
            <tr>
                <td>{{ $producto->id }}</td>
                <td>{{ $producto->fecha }}</td>
                <td>{{ $producto->boleta }}</td>
                <td>{{ $producto->cliente }}</td>
                <td>{{ $producto->ruc_dni }}</td>
                <td>{{ $producto->producto }}</td>
                <td>{{ $producto->codigoproducto }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>{{ $producto->importe }}</td>
                <td>{{ $producto->igv }}</td>
                <td>{{ $producto->ganancia }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Gráfico -->
    <div class="chart-container">
        <h2>Gráfico Detallado de ventas</h2>
        {{-- <img src="data:image/png;base64,{{ base64_encode($chart) }}" alt="Gráfico"> --}}

        {{-- <img src="{{ public_path('chart.png') }}" alt="Gráfico de Stock por Categoría"> --}}
        {{-- <img src="{{ asset('chart.png') }}" alt="Gráfico de Stock por Categoría"> --}}
        <img src="data:image/png;base64,{{ $chart }}" alt="Gráfico de Stock por Categoría">

    </div>
</body>
</html>
