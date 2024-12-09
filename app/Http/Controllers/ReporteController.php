<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use PHPUnit\Framework\Attributes\Group;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $valores = array();
        $nombres = array();
        if ($request->valoraño == "") {
            $valoraño = '2024';
        }
        else{
        $valoraño = $request->valoraño;
        }
        $años= DetalleVenta::select(DB::raw("year(ventas.created_at)	as año"))
        ->join('ventas','ventas.id','=','venta_detalle.id_venta')
        ->groupBy(DB::raw("Year(ventas.created_at)"))->get();

       // $canti = 0;
        // foreach ($libros as $row) {
        //     $revenue[] = $row["Stocklibro"];
        //     $nombres[] = $row["Titulo"];

        // }
            
        // $librosprestamos = DB::select('call reporte1');
        // $libross =DetallePrestamo::join('prestamo','prestamo.PrestamoID','=','prestamo_detalle.PrestamoID')
        // ->where('Estadohabprestamo','=',1)->get();

        //$libro= DetallePrestamo::select(DB::raw("case month(Fecharegistroprestamo) when 1 then 'Ene' when 2 then 'Feb' when 3 then 'Mar' when 4 then 'Abr' when 5 then 'May' when 6 then 'Jun' when 7 then 'Jul' when 8 then 'Ago' when 9 then 'Sep' when 10 then 'Oct' when 11 then 'Nov' when 12 then 'Dic' end as Mes"));
        
        // $ventas= DetalleVenta::select(DB::raw("SUM(cantidad)	as Cantidad"))
        // ->join('ventas','ventas.id','=','venta_detalle.id_venta')
        // ->where('estadoventa','=',1)
        // ->whereYear('ventas.created_at','=',$valoraño)
        // ->groupBy(DB::raw("Month(ventas.created_at)"))
        // //->pluck('Cantidad')
        // ->get();

        $ventas= Venta::select(DB::raw("COUNT( DISTINCT ventas.id)	as Cantidad"))
        //->join('ventas','ventas.id','=','venta_detalle.id_venta')
        ->where('ventas.estadoventa','=',1)
        ->whereYear('ventas.created_at','=',$valoraño)
        ->groupBy(DB::raw("Month(ventas.created_at)"))
        //->pluck('Cantidad')
        ->get();

        $months= Venta::select(DB::raw("month(ventas.created_at)	as Mes"))
        //->join('ventas','ventas.id','=','venta_detalle.id_venta')
        ->whereYear('ventas.created_at','=',$valoraño)
        ->groupBy(DB::raw("Month(ventas.created_at)"))
        //->pluck('Mes')
        ->get();
        
        //$datas = array(0,0,0,0,0,0,0,0,0,0,0,0);
        //Nrocopiasprestamo	as Cantidad from prestamo	P inner join prestamo_detalle PD				ON PD.PrestamoID=P.PrestamoID where year(P.Fecharegistroprestamo)='2023' group by month(P.Fecharegistroprestamo) order by month(P.Fecharegistroprestamo);"))->get();
        foreach ($months as $row) {
            
        //$nombres[] = $row["Mes"];

        if ($row["Mes"] ==1) {
            $nombres[] = "Ene";
        }
        if ($row["Mes"] ==2) {
            $nombres[] = "Feb";
        }
        if ($row["Mes"] ==3) {
            $nombres[] = "Mar";
        }
        if ($row["Mes"] ==4) {
            $nombres[] = "Abr";
        }
        if ($row["Mes"] ==5) {
            $nombres[] = "May";
        }
        if ($row["Mes"] ==6) {
            $nombres[] = "Jun";
        }
        if ($row["Mes"] ==7) {
            $nombres[] = "Jul";
        }
        if ($row["Mes"] ==8) {
            $nombres[] = "Ago";
        }
        if ($row["Mes"] ==9) {
            $nombres[] = "Sep";
        }
        if ($row["Mes"] ==10) {
            $nombres[] = "Oct";
        }
        if ($row["Mes"] ==11) {
            $nombres[] = "Nov";
        }
        if ($row["Mes"] ==12) {
            $nombres[] = "Dic";
        }

        }
        foreach ($ventas as $row) {
            
            $valores[] = $row["Cantidad"];
    
            }

        //graficos barchart density
        $valores2 = array();
        //$nombres2 = array();

        $productosvendidos= DetalleVenta::select(DB::raw("COUNT(venta_detalle.cantidad)	as Cantidad"))
        ->join('ventas','ventas.id','=','venta_detalle.id_venta')
        ->where('estadoventa','=',1)
        ->whereYear('ventas.created_at','=',$valoraño)
        ->groupBy(DB::raw("Month(ventas.created_at)"))
        //->pluck('Cantidad')
        ->get();

        foreach ($productosvendidos as $row) {
            
            $valores2[] = $row["Cantidad"];
    
            }

        $valores3 = array();
        $nombres3 = array();

        $productosporcategoria= DB::select('select c.nombre_categoria as categoria, SUM(vd.cantidad) as ventas from ventas v inner join venta_detalle vd on v.id=vd.id_venta inner join productos p on vd.id_producto=p.id inner join categorias c on p.id_categoria=c.id where v.estadoventa=1 and Year(v.created_at)='.$valoraño.'  group by c.nombre_categoria');


        foreach ($productosporcategoria as $row) {
            
            $nombres3[] = $row->categoria;
            $valores3[] = $row->ventas;
    
        }

            // Consulta para el desempeño de vendedores
        $vendedoresDesempeño = DB::select("
            SELECT u.name AS vendedor, COUNT( DISTINCT v.id) AS ventas_realizadas, SUM(vd.preciopoducto * vd.cantidad) AS monto_total 
            FROM ventas v
            INNER JOIN venta_detalle vd ON v.id = vd.id_venta
            INNER JOIN productos p ON vd.id_producto = p.id
            INNER JOIN users u ON v.idusuario = u.id
            WHERE v.estadoventa = 1 AND YEAR(v.created_at) = ?
            GROUP BY u.name
        ", [$valoraño]);

        // Preparar datos para el gráfico
        $nombresVendedores = [];
        $ventasRealizadas = [];
        $montoTotal = [];

        foreach ($vendedoresDesempeño as $row) {
            $nombresVendedores[] = $row->vendedor;
            $ventasRealizadas[] = $row->ventas_realizadas;
            $montoTotal[] = $row->monto_total;
        }
         
        //desempeño por mes
        $ventasPorMes = Venta::select(
            DB::raw("MONTH(created_at) as mes"),
            DB::raw("COUNT(id) as num_ventas"),
            DB::raw("SUM(total_pagar) as monto_total")
        )
        ->where('estadoventa', 1)
        ->whereYear('created_at', $valoraño)
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->orderBy(DB::raw("MONTH(created_at)"))
        ->get();
    
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $nombresMeses = [];
        $numVentas = [];
        $montosTotales = [];
    
        foreach ($ventasPorMes as $venta) {
            $nombresMeses[] = $meses[$venta->mes - 1];
            $numVentas[] = $venta->num_ventas;
            $montosTotales[] = $venta->monto_total;
        }

        //soles por categoria pie chart
        $valores4 = array();
        $nombres4 = array();

        $solesporcategoria= DB::select('select c.nombre_categoria as categoria, SUM(vd.preciopoducto * vd.cantidad) AS monto_total from ventas v inner join venta_detalle vd on v.id=vd.id_venta inner join productos p on vd.id_producto=p.id inner join categorias c on p.id_categoria=c.id where v.estadoventa=1 and Year(v.created_at)='.$valoraño.'  group by c.nombre_categoria');


        foreach ($solesporcategoria as $row) {
            
            $nombres4[] = $row->categoria;
            $valores4[] = $row->monto_total;
    
        }

        return view('reportes.graficos', compact('valores','nombres','años','valoraño','valores2','nombres3','valores3','nombresVendedores', 'ventasRealizadas', 'montoTotal','nombresMeses', 'numVentas', 'montosTotales','nombres4','valores4'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reportes.index');
    }

    //Stock
    public function reporteStock(Request $request){


        if ($request->ajax()) {
			$data = DB::table('productos as p')
            ->where('p.estadoproducto', '=', 1)
            ->join('categorias as c', 'p.id_categoria', '=', 'c.id')
            ->select(
                'p.id as id',
                'p.codigo as codigo',
                'p.nombre_producto as nombre',
                'c.nombre_categoria as categoria',
                'p.stock as stock',
                'p.precio_compra as preciocompra',
                'p.precio_venta as precioventa',
                DB::raw("ROUND(p.precio_venta / 1.18, 2) as precio_sin_igv"), // Cálculo del precio sin IGV
                DB::raw("ROUND((p.precio_venta / 1.18) * p.stock, 2) as valorizado") // Valorizado
            )
            ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                
                
                ->make(true);
        }

        $stockporcategoria= DB::select('select c.nombre_categoria as categoria, SUM(p.stock) as stock from productos p inner join categorias c on p.id_categoria=c.id where p.estadoproducto=1 group by c.nombre_categoria');

        foreach ($stockporcategoria as $row) {
            
            $nombres[] = $row->categoria;
            $valores[] = $row->stock;
    
        }

        return view('reportes.reporteStock', compact('nombres','valores'));
    }


    public function exportarPDF(Request $request)
    {
        // Obtén los datos de la tabla
        $productos = DB::table('productos as p')
            ->where('p.estadoproducto', '=', 1)
            ->join('categorias as c', 'p.id_categoria', '=', 'c.id')
            ->select(
                'p.id as id',
                'p.codigo as codigo',
                'p.nombre_producto as nombre',
                'c.nombre_categoria as categoria',
                'p.stock as stock',
                'p.precio_compra as preciocompra',
                'p.precio_venta as precioventa',
                DB::raw("ROUND(p.precio_venta / 1.18, 2) as precio_sin_igv"),
                DB::raw("ROUND((p.precio_venta / 1.18) * p.stock, 2) as valorizado")
            )
            ->get();

        // Obtén los datos del gráfico
        $stockporcategoria = DB::select('SELECT c.nombre_categoria as categoria, SUM(p.stock) as stock FROM productos p INNER JOIN categorias c ON p.id_categoria=c.id WHERE p.estadoproducto=1 GROUP BY c.nombre_categoria');
        $nombres = [];
        $valores = [];

        foreach ($stockporcategoria as $row) {
            $nombres[] = $row->categoria;
            $valores[] = $row->stock;
        }

        // Decodifica el gráfico enviado como imagen base64 desde el frontend
        // $chart = $request->input('chart'); // Gráfico enviado desde el formulario
        // $chart = str_replace('data:image/png;base64,', '', $chart); // Limpia el prefijo
        // $chart = str_replace(' ', '+', $chart); // Reemplaza espacios en blanco
        // $chartImage = base64_decode($chart); // Decodifica la imagen

        // // Guarda la imagen temporalmente en el servidor
        // $chartPath = public_path('chart.png');
        // file_put_contents($chartPath, $chartImage);

        // if (!file_exists($chartPath)) {
        //     return response()->json(['error' => 'No se pudo guardar la imagen del gráfico.'], 500);
        // }
        // // Renderiza la vista para el PDF
        // $pdf = Pdf::loadView('reportes.pdfReporteStock', [
        //     'productos' => $productos,
        //     'nombres' => $nombres,
        //     'valores' => $valores,
        //     'chart' => $chartPath
        // ]);

        // // Elimina la imagen temporal
        // unlink($chartPath);

        // Decodifica el gráfico enviado como imagen base64 desde el frontend
        $chart = $request->input('chart'); // Gráfico enviado desde el formulario
        $chart = str_replace('data:image/png;base64,', '', $chart); // Limpia el prefijo
        $chart = str_replace(' ', '+', $chart); // Reemplaza espacios en blanco
        $chartImage = base64_decode($chart); // Decodifica la imagen

        // Convierte la imagen a base64
        $chartBase64 = base64_encode($chartImage);

        // Renderiza la vista para el PDF
        $pdf = Pdf::loadView('reportes.pdfReporteStock', [
            'productos' => $productos,
            'nombres' => $nombres,
            'valores' => $valores,
            'chart' => $chartBase64 // Pasa la imagen en base64
        ]);


        // Descarga el PDF generado
        return $pdf->download('reporte-stock.pdf');
    }
    

    public function exportarDinamicoPDF(Request $request)
    {
        $productos=Producto::all();
        // if (empty($request->producto_id)) {
        //     # code...
        // } else {
        //     # code...
        // }
        
        $idproducto = $request->producto_id;
        $agrupacion = $request->input('agrupacion', 'mes');
        $tipo_grafico = $request->input('tipo_grafico', 'bar');
        $fechaActual = Carbon::now();

        // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
        $fecha_inicio = $request->fecha_inicio 
            ? Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay() 
            : $fechaActual->copy()->subMonth()->startOfDay();

        $fecha_fin = $request->fecha_fin 
            ? Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay() 
            : $fechaActual->copy()->endOfDay();
        // Obtén los datos de la tabla
        $data = DB::table('venta_detalle as vd')
                        ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
                        ->where('v.estadoventa', '=', 1)
                        //->where('vd.id_producto','=',$idproducto)
                        ->when(!empty($idproducto), function ($query) use ($idproducto) {
                            return $query->where('vd.id_producto', '=', $idproducto);
                        })
                        ->join('productos as p', 'vd.id_producto', '=', 'p.id')
                        ->join('clientes as c', 'v.id_cliente', '=', 'c.id')
                        ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id')
                        ->whereBetween('vd.created_at', [$fecha_inicio, $fecha_fin])
                        ->select(
                            'vd.id as id',
                            DB::raw("IFNULL(DATE_FORMAT(vd.created_at, '%d-%m-%Y'), 'Sin fecha') as fecha"),
                            DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"),
                            'c.nombre_cliente as cliente',
                            'c.dni_ruc as ruc_dni',
                            'p.nombre_producto as producto',
                            'p.codigo as codigoproducto',
                            'vd.cantidad as cantidad',
                            DB::raw("ROUND(vd.cantidad * vd.preciopoducto, 2) as importe"),
                            DB::raw("ROUND((vd.cantidad * vd.preciopoducto) - ((vd.cantidad * vd.preciopoducto) / 1.18), 2) as igv"),
                            DB::raw("CASE 
                                WHEN vd.preciopoducto IS NULL OR vd.preciocompraproducto IS NULL THEN 0
                                ELSE ROUND((((vd.preciopoducto / 1.18) - vd.preciocompraproducto) * vd.cantidad), 2) 
                            END as ganancia")
                        )
        ->get();

        // Obtener los datos para el gráfico
        $ventasPorFecha = DB::table('venta_detalle as vd')
        ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
        ->where('v.estadoventa', '=', 1)
        ->when(!empty($idproducto), function ($query) use ($idproducto) {
            return $query->where('vd.id_producto', '=', $idproducto);
        })
        ->whereBetween('vd.created_at', [$fecha_inicio, $fecha_fin])
        ->when($agrupacion == 'mes', function ($query) {
            return $query->selectRaw('MONTH(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                ->groupBy('grupo');
        })
        ->when($agrupacion == 'semana', function ($query) {
            return $query->selectRaw('WEEK(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                ->groupBy('grupo');
        })
        ->when($agrupacion == 'dia', function ($query) {
            return $query->selectRaw('DAYOFWEEK(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                ->groupBy('grupo');
        })
        ->when($agrupacion == 'anio', function ($query) {
            return $query->selectRaw('YEAR(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                ->groupBy('grupo');
        })
        ->get();

        Log::info('Ventas por fecha:', $ventasPorFecha->toArray());
            

        // Preparar los datos para el gráfico
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $labels = [];
        $numVentas = [];
        $montosTotales = [];

        foreach ($ventasPorFecha as $venta) {
            switch ($agrupacion) {
                case 'mes':
                    $labels[] = $meses[$venta->grupo - 1];
                    break;
                case 'semana':
                    $labels[] = 'Semana-' . $venta->grupo;
                    break;
                case 'dia':
                    $labels[] = $dias[$venta->grupo - 1];
                    break;
                case 'anio':
                    $labels[] = $venta->grupo;
                    break;
            }
            $numVentas[] = $venta->numVentas;
            $montosTotales[] = $venta->montoTotal;
        }

        // Formato final para el gráfico
        $graficoData = [
            'labels' => $labels,
            'numVentas' => $numVentas,
            'montosTotales' => $montosTotales,
            'agrupacion' => $agrupacion,
            'tipo_grafico' => $tipo_grafico
        ];

        // Decodifica el gráfico enviado como imagen base64 desde el frontend
        $chart = $request->input('chart'); // Gráfico enviado desde el formulario
        $chart = str_replace('data:image/png;base64,', '', $chart); // Limpia el prefijo
        $chart = str_replace(' ', '+', $chart); // Reemplaza espacios en blanco
        $chartImage = base64_decode($chart); // Decodifica la imagen

        // Convierte la imagen a base64
        $chartBase64 = base64_encode($chartImage);

        // Renderiza la vista para el PDF
        $pdf = Pdf::loadView('reportes.pdfReporteDinamico', [
            'data' => $data,
            'graficoData' => $graficoData,
            'chart' => $chartBase64 // Pasa la imagen en base64
        ]);


        // Descarga el PDF generado
        return $pdf->download('reporte-dinamico.pdf');
    }

    public function reporteVentaDetallada(Request $request)
    {
        $fechaActual = Carbon::now();

        // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
        $fechaInicio = $request->fechaInicio 
            ? Carbon::createFromFormat('Y-m-d', $request->fechaInicio)->startOfDay() 
            : $fechaActual->copy()->subMonth()->startOfDay();

        $fechaFin = $request->fechaFin 
            ? Carbon::createFromFormat('Y-m-d', $request->fechaFin)->endOfDay() 
            : $fechaActual->copy()->endOfDay();

        if ($request->ajax()) {
            try {
                $data = DB::table('venta_detalle as vd')
                    ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
                    ->where('v.estadoventa', '=', 1)
                    ->join('productos as p', 'vd.id_producto', '=', 'p.id')
                    ->join('clientes as c', 'v.id_cliente', '=', 'c.id')
                    ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id')
                    ->whereBetween('vd.created_at', [$fechaInicio, $fechaFin])
                    ->select(
                        'vd.id as id',
                        DB::raw("IFNULL(DATE_FORMAT(vd.created_at, '%d-%m-%Y'), 'Sin fecha') as fecha"),
                        DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"),
                        'c.nombre_cliente as cliente',
                        'c.dni_ruc as ruc_dni',
                        'p.nombre_producto as producto',
                        'p.codigo as codigoproducto',
                        'vd.cantidad as cantidad',
                        DB::raw("ROUND(vd.cantidad * vd.preciopoducto, 2) as importe"),
                        DB::raw("ROUND((vd.cantidad * vd.preciopoducto) - ((vd.cantidad * vd.preciopoducto) / 1.18), 2) as igv"),
                        DB::raw("CASE 
                            WHEN vd.preciopoducto IS NULL OR vd.preciocompraproducto IS NULL THEN 0
                            ELSE ROUND((((vd.preciopoducto / 1.18) - vd.preciocompraproducto) * vd.cantidad), 2) 
                        END as ganancia")
                    )
                    ->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);

                
            } catch (\Exception $e) {
                Log::error('Error en reporteVentaDetallada: ' . $e->getMessage());
                return response()->json(['error' => 'Error procesando los datos.'], 500);
            }
        }

        //desempeño por mes
        $ventasPorMes = Venta::select(
            DB::raw("MONTH(created_at) as mes"),
            DB::raw("COUNT(id) as num_ventas"),
            DB::raw("SUM(total_pagar) as monto_total")
        )
        ->where('estadoventa', 1)
        //->whereYear('created_at', $valoraño)
        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->orderBy(DB::raw("MONTH(created_at)"))
        ->get();
    
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $nombresMeses = [];
        $numVentas = [];
        $montosTotales = [];
    
        foreach ($ventasPorMes as $venta) {
            $nombresMeses[] = $meses[$venta->mes - 1];
            $numVentas[] = $venta->num_ventas;
            $montosTotales[] = $venta->monto_total;
        }

        // Pasar fechas inicial y final predeterminadas a la vista
        return view('reportes.reporteVentaDetallada', [
            'fechaInicio' => $fechaInicio->format('Y-m-d'),
            'fechaFin' => $fechaFin->format('Y-m-d'),
            'nombresMeses' => $nombresMeses,
            'numVentas' => $numVentas,
            'montosTotales' => $montosTotales

        ]);

    }

    public function reporteProducto(Request $request){

        $productos=Producto::all();
        // if (empty($request->producto_id)) {
        //     # code...
        // } else {
        //     # code...
        // }
        
        $idproducto = $request->producto_id;
        $agrupacion = $request->input('agrupacion', 'mes');
        $tipo_grafico = $request->input('tipo_grafico', 'bar');
        $fechaActual = Carbon::now();

        // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
        $fecha_inicio = $request->fecha_inicio 
            ? Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay() 
            : $fechaActual->copy()->subMonth()->startOfDay();

        $fecha_fin = $request->fecha_fin 
            ? Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay() 
            : $fechaActual->copy()->endOfDay();

            if ($request->ajax()) {
                try {
                        $data = DB::table('venta_detalle as vd')
                        ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
                        ->where('v.estadoventa', '=', 1)
                        //->where('vd.id_producto','=',$idproducto)
                        ->when(!empty($idproducto), function ($query) use ($idproducto) {
                            return $query->where('vd.id_producto', '=', $idproducto);
                        })
                        ->join('productos as p', 'vd.id_producto', '=', 'p.id')
                        ->join('clientes as c', 'v.id_cliente', '=', 'c.id')
                        ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id')
                        ->whereBetween('vd.created_at', [$fecha_inicio, $fecha_fin])
                        ->select(
                            'vd.id as id',
                            DB::raw("IFNULL(DATE_FORMAT(vd.created_at, '%d-%m-%Y'), 'Sin fecha') as fecha"),
                            DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"),
                            'c.nombre_cliente as cliente',
                            'c.dni_ruc as ruc_dni',
                            'p.nombre_producto as producto',
                            'p.codigo as codigoproducto',
                            'vd.cantidad as cantidad',
                            DB::raw("ROUND(vd.cantidad * vd.preciopoducto, 2) as importe"),
                            DB::raw("ROUND((vd.cantidad * vd.preciopoducto) - ((vd.cantidad * vd.preciopoducto) / 1.18), 2) as igv"),
                            DB::raw("CASE 
                                WHEN vd.preciopoducto IS NULL OR vd.preciocompraproducto IS NULL THEN 0
                                ELSE ROUND((((vd.preciopoducto / 1.18) - vd.preciocompraproducto) * vd.cantidad), 2) 
                            END as ganancia")
                        )
                        ->get();
                    
                        // Obtener los datos para el gráfico
                        $ventasPorFecha = DB::table('venta_detalle as vd')
                        ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
                        ->where('v.estadoventa', '=', 1)
                        ->when(!empty($idproducto), function ($query) use ($idproducto) {
                            return $query->where('vd.id_producto', '=', $idproducto);
                        })
                        ->whereBetween('vd.created_at', [$fecha_inicio, $fecha_fin])
                        ->when($agrupacion == 'mes', function ($query) {
                            return $query->selectRaw('MONTH(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                                ->groupBy('grupo');
                        })
                        ->when($agrupacion == 'semana', function ($query) {
                            return $query->selectRaw('WEEK(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                                ->groupBy('grupo');
                        })
                        ->when($agrupacion == 'dia', function ($query) {
                            return $query->selectRaw('DAYOFWEEK(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                                ->groupBy('grupo');
                        })
                        ->when($agrupacion == 'anio', function ($query) {
                            return $query->selectRaw('YEAR(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                                ->groupBy('grupo');
                        })
                        ->get();

                    Log::info('Ventas por fecha:', $ventasPorFecha->toArray());
                        

                    // Preparar los datos para el gráfico
                    $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
                    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    $labels = [];
                    $numVentas = [];
                    $montosTotales = [];

                    foreach ($ventasPorFecha as $venta) {
                        switch ($agrupacion) {
                            case 'mes':
                                $labels[] = $meses[$venta->grupo - 1];
                                break;
                            case 'semana':
                                $labels[] = 'Semana-' . $venta->grupo;
                                break;
                            case 'dia':
                                $labels[] = $dias[$venta->grupo - 1];
                                break;
                            case 'anio':
                                $labels[] = $venta->grupo;
                                break;
                        }
                        $numVentas[] = $venta->numVentas;
                        $montosTotales[] = $venta->montoTotal;
                    }

                    // Formato final para el gráfico
                    $graficoData = [
                        'labels' => $labels,
                        'numVentas' => $numVentas,
                        'montosTotales' => $montosTotales,
                        'agrupacion' => $agrupacion,
                        'tipo_grafico' => $tipo_grafico
                    ];

                    // Retorno de los datos
                    return response()->json([
                        'tabla' => DataTables::of($data)->addIndexColumn()->make(true),
                        'grafico' => $graficoData,
                    ]);
                    
                   // $graficoData = $this->reporteProductoGrafico($request);   
    
                    // return DataTables::of($data)
                    //      ->addIndexColumn()
                    //      ->make(true);

                    // Retornar los datos en formato DataTables, añadiendo los datos gráficos
                    // return response()->json([
                    //     'data' => DataTables::of($data)->addIndexColumn()->make(true),
                    //     'graficoData' => $graficoData // Enviar los datos del gráfico
                        
                    // ]);
                    // Usa DataTables para paginar, filtrar y ordenar correctamente
                    // $dataTable = DataTables::of($data)->addIndexColumn();

                    // return response()->json([
                    //     'draw' => intval($request->draw),          // Valor 'draw' enviado desde el frontend
                    //     'recordsTotal' => $dataTable->count(),    // Total de registros sin filtrar
                    //     'recordsFiltered' => $dataTable->count(), // Total de registros filtrados
                    //     'data' => $dataTable->toArray()['data'],  // Datos en formato JSON
                    //     'graficoData' => $graficoData,             // Información adicional para el gráfico
                    //     'error' => false,
                    // ]);
            
                    
                } catch (\Exception $e) {
                    Log::error('Error en reporteProductos: ' . $e->getMessage());
                    return response()->json(['error' => 'Error procesando los datos.'], 500);
                }
            }

            return view('reportes.reporteproducto',[
                'fecha_inicio' => $fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $fecha_fin->format('Y-m-d'),
                'productos' => $productos,
            ]);


        
        

    }

    public function reporteProductoGrafico(Request $request){
        
        Log::info('Datos recibidos:', $request->all());
        

        if ($request->ajax()) {

            $agrupacion=$request->agrupacion;
        $idproducto =$request->producto_id;
        $tipoGrafico = $request->tipo_grafico;

        $fechaActual = Carbon::now();

        // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
        $fecha_inicio = $request->fecha_inicio 
            ? Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay() 
            : $fechaActual->copy()->subMonth()->startOfDay();

        $fecha_fin = $request->fecha_fin 
            ? Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay() 
            : $fechaActual->copy()->endOfDay();

            try {
                // Obtener los datos para el gráfico
                $ventasPorFecha = DB::table('venta_detalle as vd')
                    ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
                    ->where('v.estadoventa', '=', 1)
                    // ->when(!empty($idproducto), function ($query) use ($idproducto) {
                    //     return $query->where('vd.id_producto', '=', $idproducto);
                    // })
                    ->when($idproducto !== '', function ($query) use ($idproducto) {
                        return $query->where('vd.id_producto', '=', $idproducto);
                    })
                    ->whereBetween('vd.created_at', [$fecha_inicio, $fecha_fin])
                    ->when($agrupacion == 'mes', function ($query) {
                        return $query->selectRaw('MONTH(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                            ->groupBy('grupo');
                    })
                    ->when($agrupacion == 'semana', function ($query) {
                        return $query->selectRaw('WEEK(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                            ->groupBy('grupo');
                    })
                    ->when($agrupacion == 'dia', function ($query) {
                        return $query->selectRaw('DAYOFWEEK(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                            ->groupBy('grupo');
                    })
                    ->when($agrupacion == 'anio', function ($query) {
                        return $query->selectRaw('YEAR(vd.created_at) as grupo, COUNT(*) as numVentas, SUM(vd.cantidad * vd.preciopoducto) as montoTotal')
                            ->groupBy('grupo');
                    })
                ->get();

                // Preparar los datos para el gráfico
                $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                
                $nombresMeses=[];
                $nombresSemanas = [];
                $nombresDias=[];
                $nombresAños = [];

                $labels = [];
                $numVentas = [];
                $montosTotales = [];

        
                // Dependiendo de la agrupación seleccionada, se preparan los datos
                if ($agrupacion == 'mes') {
                    array_splice($labels, 0);
                    foreach ($ventasPorFecha as $venta) {
                        $nombresMeses[] = $meses[$venta->grupo - 1];
                        $numVentas[] = $venta->num_ventas;
                        $montosTotales[] = $venta->montoTotal;

                        $labels[] = $meses[$venta->grupo - 1];
                    }
                } elseif ($agrupacion == 'semana') {
                    array_splice($labels, 0);
                    //$labels = $ventasPorFecha->pluck('semana');
                    foreach ($ventasPorFecha as $venta) {
                        $nombresSemanas[] = 'semana-'.$venta->grupo;
                        $numVentas[] = $venta->num_ventas;
                        $montosTotales[] = $venta->montoTotal;

                        $labels[] = 'semana-'.$venta->grupo;
                    }
                } elseif ($agrupacion == 'dia') {
                    array_splice($labels, 0);
                    //$labels = $nombresDias;
                    foreach ($ventasPorFecha as $venta) {
                        $nombresDias[] = $dias[$venta->grupo - 1];
                        $numVentas[] = $venta->num_ventas;
                        $montosTotales[] = $venta->montoTotal;

                        $labels[] = $dias[$venta->grupo - 1];
                    }
                } elseif ($agrupacion == 'anio') {
                    array_splice($labels, 0);
                    //$labels = $ventasPorFecha->pluck('anio');
                    foreach ($ventasPorFecha as $venta) {
                        $nombresAños[] = $venta->grupo;
                        $numVentas[] = $venta->num_ventas;
                        $montosTotales[] = $venta->montoTotal;

                        $labels[] = $venta->grupo;

                        
                    }
                }

                // if ($ventasPorFecha->isEmpty()) {
                //     return response()->json([
                //         'error' => true,
                //         'message' => 'No se encontraron datos para la agrupación seleccionada.',
                //         'labels' => [],
                //         'numVentas' => [],
                //         'montosTotales' => []
                //     ]);
                // }

                // $numVentas = $ventasPorFecha->pluck('numVentas');
                // $montosTotales = $ventasPorFecha->pluck('montoTotal');

                // Retornar la respuesta con los datos deel gráfico
                return response()->json([
                    
                    'labels' => $labels ,
                    'numVentas' => $numVentas,
                    'montosTotales' => $montosTotales,
                    'agrupacion' => $agrupacion,
                    'tipoGrafico' => $tipoGrafico,
                    'error' => false,
                ]);

                // return DataTables::of($data)
                //     ->addIndexColumn()
                //     ->make(true);
            } catch (\Throwable $e) {
                // Log::error('Error en reporteProductos: ' . $e->getMessage());
                //         return response()->json(['error' => 'Error procesando los datos.'], 500);
                Log::error('Error en reporteProductoGrafico: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->all()
                ]);
                return [
                    'error' => true,
                    'message' => 'Hubo un problema al generar los datos del gráfico.'
                ];
            }
        }
    }


    // public function obtenerVentas(Request $request)
    // {
    //     $fecha_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay();
    //     $fecha_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay();
    //     // Filtrar las ventas por producto y fecha
    //     $ventas = Venta::with(['boleta', 'cliente', 'detalles.productos'])
    //         ->when($request->producto_id, function ($query) use ($request) {
    //             $query->whereHas('detalles', function ($query) use ($request) {
    //                 $query->where('id_producto', $request->producto_id);
    //             });
    //         })
    //         ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
    //         ->get();

    //     // Formatear los datos para la tabla
    //     $data = $ventas->map(function ($venta) {
    //         return [
    //             'boleta' => $venta->boleta ? 
    //             ($venta->boleta->serie && $venta->boleta->numero 
    //                 ? $venta->boleta->serie . '-' . $venta->boleta->numero 
    //                 : 'NO GENERADO')
    //             : 'NO GENERADO',
    //             'cliente' => $venta->cliente->nombre_cliente,
    //             'producto' => $venta->detalles->first()->productos->nombre_producto,
    //             'cantidad' => $venta->detalles->sum('cantidad'),
    //             'monto' => $venta->boleta->monto,
    //         ];
    //     });

    //     return response()->json([
    //         'draw' => $request->draw,
    //         'recordsTotal' => $data->count(),
    //         'recordsFiltered' => $data->count(),
    //         'data' => $data,
    //     ]);
    // }

    // public function generarGrafico(Request $request)
    // {
    //     // Filtrar las ventas por producto y fecha
    //     $fecha_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay();
    //     $fecha_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay();
    //     // Filtrar las ventas por producto y fecha
    //     $ventas = Venta::with(['boleta', 'cliente', 'detalles.productos'])
    //         ->when($request->producto_id, function ($query) use ($request) {
    //             $query->whereHas('detalles', function ($query) use ($request) {
    //                 $query->where('id_producto', $request->producto_id);
    //             });
    //         })
    //         ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
    //         ->get();


    //     // Preparar los datos para el gráfico
    //     $labels = [];
    //     $data = [];
    //     $colors = []; // Para los gráficos de pastel

    //     foreach ($ventas as $venta) {
    //         $fecha = \Carbon\Carbon::parse($venta->created_at);
    //         $labels[] = $fecha->format('d/m/Y');
    //         $data[] = $venta->boleta->monto;
    //         $colors[] = '#' . substr(md5(rand()), 0, 6); // Colores aleatorios para pastel
    //     }

    //     return response()->json([
    //         'labels' => $labels,
    //         'data' => $data,
    //         'colors' => $colors, // Colores para pastel
    //     ]);
    // }

    // public function generarGrafico(Request $request)
    // {
    //     $fecha_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->startOfDay();
    //     $fecha_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->endOfDay();

    //     // Filtrar las ventas por producto y fecha
    //     $ventas = Venta::with(['boleta', 'cliente', 'detalles.productos'])
    //         ->when($request->producto_id, function ($query) use ($request) {
    //             $query->whereHas('detalles', function ($query) use ($request) {
    //                 $query->where('id_producto', $request->producto_id);
    //             });
    //         })
    //         ->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);

    //     // Agrupar las ventas según el tipo de agrupación (día, semana, mes)
    //     if ($request->agrupacion == 'semana') {
    //         $ventas = $ventas->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(boletas.monto) as monto')
    //                         ->groupBy('year', 'week')
    //                         ->orderBy('year', 'desc')
    //                         ->orderBy('week', 'desc');
    //     } elseif ($request->agrupacion == 'mes') {
    //         $ventas = $ventas->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(boletas.monto) as monto')
    //                         ->groupBy('year', 'month')
    //                         ->orderBy('year', 'desc')
    //                         ->orderBy('month', 'desc');
    //     } else {
    //         // Agrupación por día (por defecto)
    //         $ventas = $ventas->selectRaw('DATE(created_at) as date, SUM(boletas.monto) as monto')
    //                         ->groupBy('date')
    //                         ->orderBy('date', 'desc');
    //     }

    //     // Obtener los datos
    //     $ventas = $ventas->get();

    //     // Preparar los datos para el gráfico
    //     $labels = [];
    //     $data = [];
    //     $colors = []; // Para los gráficos de pastel

    //     foreach ($ventas as $venta) {
    //         // Dependiendo de la agrupación, formateamos la fecha
    //         if ($request->agrupacion == 'semana') {
    //             $fecha = Carbon::create($venta->year, 1, 1)->addWeeks($venta->week - 1);
    //             $labels[] = $fecha->format('W, Y'); // Semana, Año
    //         } elseif ($request->agrupacion == 'mes') {
    //             $fecha = Carbon::create($venta->year, $venta->month, 1)->format('m, Y'); // Mes, Año
    //             $labels[] = $fecha;
    //         } else {
    //             $labels[] = Carbon::parse($venta->date)->format('d/m/Y'); // Día
    //         }

    //         $data[] = $venta->monto;
    //         $colors[] = '#' . substr(md5(rand()), 0, 6); // Colores aleatorios para pastel
    //     }

    //     return response()->json([
    //         'labels' => $labels,
    //         'data' => $data,
    //         'colors' => $colors, // Colores para pastel
    //     ]);
    // }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    //PARA API

    public function getReportes()
    {
        $ventas = Venta::all();  // O la consulta que necesites
        return response()->json($ventas);
    }


    // public function reporteVentaDetalladaapi(Request $request)
    // {
    //     $fechaActual = Carbon::now();

    //     // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
    //     $fechaInicio = $request->fechaInicio 
    //         ? Carbon::createFromFormat('Y-m-d', $request->fechaInicio)->startOfDay() 
    //         : $fechaActual->copy()->subMonth()->startOfDay();

    //     $fechaFin = $request->fechaFin 
    //         ? Carbon::createFromFormat('Y-m-d', $request->fechaFin)->endOfDay() 
    //         : $fechaActual->copy()->endOfDay();

    //     if ($request->ajax()) {
    //         try {
    //             $data = DB::table('venta_detalle as vd')
    //                 ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
    //                 ->where('v.estadoventa', '=', 1)
    //                 ->join('productos as p', 'vd.id_producto', '=', 'p.id')
    //                 ->join('clientes as c', 'v.id_cliente', '=', 'c.id')
    //                 ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id')
    //                 ->whereBetween('vd.created_at', [$fechaInicio, $fechaFin])
    //                 ->select(
    //                     'vd.id as id',
    //                     DB::raw("IFNULL(DATE_FORMAT(vd.created_at, '%d-%m-%Y'), 'Sin fecha') as fecha"),
    //                     DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"),
    //                     'c.nombre_cliente as cliente',
    //                     'c.dni_ruc as ruc_dni',
    //                     'p.nombre_producto as producto',
    //                     'p.codigo as codigoproducto',
    //                     'vd.cantidad as cantidad',
    //                     DB::raw("ROUND(vd.cantidad * vd.preciopoducto, 2) as importe"),
    //                     DB::raw("ROUND((vd.cantidad * vd.preciopoducto) - ((vd.cantidad * vd.preciopoducto) / 1.18), 2) as igv"),
    //                     DB::raw("CASE 
    //                         WHEN vd.preciopoducto IS NULL OR vd.preciocompraproducto IS NULL THEN 0
    //                         ELSE ROUND((((vd.preciopoducto / 1.18) - vd.preciocompraproducto) * vd.cantidad), 2) 
    //                     END as ganancia")
    //                 )
    //                 ->get();

    //             return DataTables::of($data)
    //                 ->addIndexColumn()
    //                 ->make(true);

                
    //         } catch (\Exception $e) {
    //             Log::error('Error en reporteVentaDetallada: ' . $e->getMessage());
    //             return response()->json(['error' => 'Error procesando los datos.'], 500);
    //         }
    //     }

    //     //desempeño por mes
    //     $ventasPorMes = Venta::select(
    //         DB::raw("MONTH(created_at) as mes"),
    //         DB::raw("COUNT(id) as num_ventas"),
    //         DB::raw("SUM(total_pagar) as monto_total")
    //     )
    //     ->where('estadoventa', 1)
    //     //->whereYear('created_at', $valoraño)
    //     ->whereBetween('created_at', [$fechaInicio, $fechaFin])
    //     ->groupBy(DB::raw("MONTH(created_at)"))
    //     ->orderBy(DB::raw("MONTH(created_at)"))
    //     ->get();
    
    //     $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    //     $nombresMeses = [];
    //     $numVentas = [];
    //     $montosTotales = [];
    
    //     foreach ($ventasPorMes as $venta) {
    //         $nombresMeses[] = $meses[$venta->mes - 1];
    //         $numVentas[] = $venta->num_ventas;
    //         $montosTotales[] = $venta->monto_total;
    //     }

    //     // Pasar fechas inicial y final predeterminadas a la vista
    //     // return view('reportes.reporteVentaDetallada', [
    //     //     'fechaInicio' => $fechaInicio->format('Y-m-d'),
    //     //     'fechaFin' => $fechaFin->format('Y-m-d'),
    //     //     'nombresMeses' => $nombresMeses,
    //     //     'numVentas' => $numVentas,
    //     //     'montosTotales' => $montosTotales

    //     // ]);

    //     return response()->json(['success' => 'reporte venta detallada Exitosa.',
    //         'fechaInicio' => $fechaInicio->format('Y-m-d'),
    //                 'fechaFin' => $fechaFin->format('Y-m-d'),
    //                 'nombresMeses' => $nombresMeses,
    //                 'numVentas' => $numVentas,
    //                 'montosTotales' => $montosTotales
    //             ]);

    // }

    // public function reporteVentaDetalladaapi(Request $request)
    // {

    //     $fechaActual = Carbon::now();

    //     // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
    //     $fechaInicio = $request->fechaInicio
    //         ? Carbon::createFromFormat('Y-m-d', $request->fechaInicio)->startOfDay()
    //         : $fechaActual->copy()->subMonth()->startOfDay();

    //     $fechaFin = $request->fechaFin
    //         ? Carbon::createFromFormat('Y-m-d', $request->fechaFin)->endOfDay()
    //         : $fechaActual->copy()->endOfDay();

    //     try {
    //         $data = DB::table('venta_detalle as vd')
    //             ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
    //             ->where('v.estadoventa', '=', 1)
    //             ->join('productos as p', 'vd.id_producto', '=', 'p.id')
    //             ->join('clientes as c', 'v.id_cliente', '=', 'c.id')
    //             ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id')
    //             ->whereBetween('vd.created_at', [$fechaInicio, $fechaFin])
    //             ->select(
    //                 'vd.id as id',
    //                 DB::raw("IFNULL(DATE_FORMAT(vd.created_at, '%d-%m-%Y'), 'Sin fecha') as fecha"),
    //                 DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"),
    //                 'c.nombre_cliente as cliente',
    //                 'c.dni_ruc as ruc_dni',
    //                 'p.nombre_producto as producto',
    //                 'p.codigo as codigoproducto',
    //                 'vd.cantidad as cantidad',
    //                 DB::raw("ROUND(vd.cantidad * vd.preciopoducto, 2) as importe"),
    //                 DB::raw("ROUND((vd.cantidad * vd.preciopoducto) - ((vd.cantidad * vd.preciopoducto) / 1.18), 2) as igv"),
    //                 DB::raw("CASE 
    //                     WHEN vd.preciopoducto IS NULL OR vd.preciocompraproducto IS NULL THEN 0
    //                     ELSE ROUND((((vd.preciopoducto / 1.18) - vd.preciocompraproducto) * vd.cantidad), 2) 
    //                 END as ganancia")
    //             )
    //             ->get();

    //         //desempeño por mes
    //         $ventasPorMes = Venta::select(
    //             DB::raw("MONTH(created_at) as mes"),
    //             DB::raw("COUNT(id) as num_ventas"),
    //             DB::raw("SUM(total_pagar) as monto_total")
    //         )
    //         ->where('estadoventa', 1)
    //         //->whereYear('created_at', $valoraño)
    //         ->whereBetween('created_at', [$fechaInicio, $fechaFin])
    //         ->groupBy(DB::raw("MONTH(created_at)"))
    //         ->orderBy(DB::raw("MONTH(created_at)"))
    //         ->get();
        
    //         $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    //         $nombresMeses = [];
    //         $numVentas = [];
    //         $montosTotales = [];
        
    //         foreach ($ventasPorMes as $venta) {
    //             $nombresMeses[] = $meses[$venta->mes - 1];
    //             $numVentas[] = $venta->num_ventas;
    //             $montosTotales[] = $venta->monto_total;
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Reporte venta detallada exitosa.',
    //             'tabla' => DataTables::of($data)->addIndexColumn()->make(true),
    //             'grafico' => $ventasPorMes,
    //             'fechaInicio' =>$fechaInicio, 
    //             'fechaFin' =>$fechaFin
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Error en reporteVentaDetallada: ' . $e->getMessage());
    //         return response()->json(['error' => 'Error procesando los datos.'], 500);
    //     }
        
    // }
    //ajustada para flutter
    public function reporteVentaDetalladaapi(Request $request)
    {
        $fechaActual = Carbon::now();

        // Inicializar fechas predeterminadas (un mes antes y hoy) si no se reciben en el request
        $fechaInicio = $request->fechaInicio
            ? Carbon::createFromFormat('Y-m-d', $request->fechaInicio)->startOfDay()
            : $fechaActual->copy()->subMonth()->startOfDay();

        $fechaFin = $request->fechaFin
            ? Carbon::createFromFormat('Y-m-d', $request->fechaFin)->endOfDay()
            : $fechaActual->copy()->endOfDay();

        try {
            // Datos para la tabla
            $data = DB::table('venta_detalle as vd')
                ->join('ventas as v', 'vd.id_venta', '=', 'v.id')
                ->where('v.estadoventa', '=', 1)
                ->join('productos as p', 'vd.id_producto', '=', 'p.id')
                ->join('clientes as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id')
                ->whereBetween('vd.created_at', [$fechaInicio, $fechaFin])
                ->select(
                    'vd.id as id',
                    DB::raw("IFNULL(DATE_FORMAT(vd.created_at, '%d-%m-%Y'), 'Sin fecha') as fecha"),
                    DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"),
                    'c.nombre_cliente as cliente',
                    'c.dni_ruc as ruc_dni',
                    'p.nombre_producto as producto',
                    'p.codigo as codigoproducto',
                    'vd.cantidad as cantidad',
                    DB::raw("ROUND(vd.cantidad * vd.preciopoducto, 2) as importe"),
                    DB::raw("ROUND((vd.cantidad * vd.preciopoducto) - ((vd.cantidad * vd.preciopoducto) / 1.18), 2) as igv"),
                    DB::raw("CASE 
                        WHEN vd.preciopoducto IS NULL OR vd.preciocompraproducto IS NULL THEN 0
                        ELSE ROUND((((vd.preciopoducto / 1.18) - vd.preciocompraproducto) * vd.cantidad), 2) 
                    END as ganancia")
                )
                ->get();

            // Datos para el gráfico
            $ventasPorMes = Venta::select(
                DB::raw("MONTH(created_at) as mes"),
                DB::raw("COUNT(id) as num_ventas"),
                DB::raw("SUM(total_pagar) as monto_total")
            )
            ->where('estadoventa', 1)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->orderBy(DB::raw("MONTH(created_at)"))
            ->get();

            // Preparar datos para el gráfico
            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            $nombresMeses = [];
            $numVentas = [];
            $montosTotales = [];

            foreach ($ventasPorMes as $venta) {
                $nombresMeses[] = $meses[$venta->mes - 1];
                $numVentas[] = $venta->num_ventas;
                $montosTotales[] = $venta->monto_total;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Reporte venta detallada exitosa.',
                'tabla' => $data, // Datos detallados para la tabla
                'grafico' => [
                    'meses' => $nombresMeses,
                    'numVentas' => $numVentas,
                    'montosTotales' => $montosTotales
                ],
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);
        } catch (\Exception $e) {
            Log::error('Error en reporteVentaDetallada: ' . $e->getMessage());
            return response()->json(['error' => 'Error procesando los datos.'], 500);
        }
    }



    // public function reporteStockapi(Request $request){


    //     if ($request->ajax()) {
	// 		$data = DB::table('productos as p')
    //         ->where('p.estadoproducto', '=', 1)
    //         ->join('categorias as c', 'p.id_categoria', '=', 'c.id')
    //         ->select(
    //             'p.id as id',
    //             'p.codigo as codigo',
    //             'p.nombre_producto as nombre',
    //             'c.nombre_categoria as categoria',
    //             'p.stock as stock',
    //             'p.precio_compra as preciocompra',
    //             'p.precio_venta as precioventa',
    //             DB::raw("ROUND(p.precio_venta / 1.18, 2) as precio_sin_igv"), // Cálculo del precio sin IGV
    //             DB::raw("ROUND((p.precio_venta / 1.18) * p.stock, 2) as valorizado") // Valorizado
    //         )
    //         ->get();

    //         return DataTables::of($data)
    //             ->addIndexColumn()
                
                
    //             ->make(true);
    //     }

    //     $stockporcategoria= DB::select('select c.nombre_categoria as categoria, SUM(p.stock) as stock from productos p inner join categorias c on p.id_categoria=c.id where p.estadoproducto=1 group by c.nombre_categoria');

    //     foreach ($stockporcategoria as $row) {
            
    //         $nombres[] = $row->categoria;
    //         $valores[] = $row->stock;
    
    //     }

    //     return response()->json(['success' => 'reporte stock Exitosa.',
        
    //             'nombres' => $nombres,
    //             'valores' => $valores
    //         ]);

    //     //return view('reportes.reporteStock', compact('nombres','valores'));
    // }
    public function reporteStockapi(Request $request)
    {
        // Validación de solicitud AJAX
        if ($request->ajax()) {
            try {
                $data = DB::table('productos as p')
                    ->where('p.estadoproducto', '=', 1)
                    ->join('categorias as c', 'p.id_categoria', '=', 'c.id')
                    ->select(
                        'p.id as id',
                        'p.codigo as codigo',
                        'p.nombre_producto as nombre',
                        'c.nombre_categoria as categoria',
                        'p.stock as stock',
                        'p.precio_compra as preciocompra',
                        'p.precio_venta as precioventa',
                        DB::raw("ROUND(p.precio_venta / 1.18, 2) as precio_sin_igv"),
                        DB::raw("ROUND((p.precio_venta / 1.18) * p.stock, 2) as valorizado")
                    )
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Reporte stock exitoso.',
                    'data' => [
                        'productos' => $data
                    ]
                ]);
            } catch (\Exception $e) {
                Log::error('Error en reporteStockapi: ' . $e->getMessage());
                return response()->json(['error' => 'Error procesando los datos.'], 500);
            }
        }

        // Procesar reporte de stock por categoría
        $stockporcategoria = DB::select('select c.nombre_categoria as categoria, SUM(p.stock) as stock from productos p inner join categorias c on p.id_categoria=c.id where p.estadoproducto=1 group by c.nombre_categoria');

        $nombres = [];
        $valores = [];

        foreach ($stockporcategoria as $row) {
            $nombres[] = $row->categoria;
            $valores[] = $row->stock;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reporte stock por categoría exitoso.',
            'data' => [
                'nombres' => $nombres,
                'valores' => $valores
            ]
        ]);
    }



}
