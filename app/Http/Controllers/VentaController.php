<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $ventas=Venta::all();

        if ($request->ajax()) {
            try {
			// $data = DB::table('ventas as v')
            // ->where('v.estadoventa','=',1)
			// ->select('v.*')
			// ->get();
            $data = DB::table('ventas as v')
            ->join('clientes as c', 'v.id_cliente', '=', 'c.id') // Asegúrate de que el nombre de la columna sea correcto
            ->join('users as u', 'v.idusuario', '=', 'u.id') // Asegúrate de que el nombre de la columna sea correcto
            //->join('boletas as b', 'v.id', '=', 'b.venta_id') // Relación con la tabla de boletas
            ->leftJoin('boletas as b', 'v.id', '=', 'b.venta_id') // Cambiado a leftJoin para incluir ventas sin boletas
            ->join('venta_detalle as d', 'v.id', '=', 'd.id_venta')
            //->where('v.estadoventa', '=', 1)
            ->where(function ($query) {
                $query->where('v.estadoventa', '=', 1)
                      ->orWhereNull('v.estadoventa');
            })
             ->select(
                'v.id',              // Asegúrate de incluir esta columna
                'v.total_pagar',    // Asegúrate de incluir esta columna
                'c.nombre_cliente as nombre_cliente',
                DB::raw("DATE_FORMAT(v.created_at, '%d-%m-%Y') as fecha"),
                'u.email as email',
                //DB::raw("CONCAT(b.serie, '-', b.numero) as boleta"),
                DB::raw("IFNULL(CONCAT(b.serie, '-', b.numero), 'NO GENERADO') as boleta"), // Muestra "NO GENERADO" si no hay boleta
                DB::raw("SUM(d.cantidad) as total_cantidad")

             )

             ->groupBy('v.id', 'v.total_pagar','v.created_at', 'c.nombre_cliente', 'u.email', 'b.serie', 'b.numero') // Incluye todas las columnas seleccionadas

            ->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteVenta"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Ver" class="btn btn-warning btn-sm eyeVenta"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                    return $btn;
                })
                // ->addColumn('action4', function ($row) {
                //     //$btn = '<a href="" data-toggle="tooltip"  data-id="' . $row->id . '" target="_blank" data-original-title="Descargar" class="btn btn-primary btn-sm descargarVenta"><i class="fas fa-download"></i></a>';
                //     $btn = '<a href="' . route('venta.show', $row->id) . '" target="_blank" data-toggle="tooltip" data-original-title="Descargar" class="btn btn-primary btn-sm"><i class="fas fa-download"></i></a>';
                //     return $btn;
                // })
                ->addColumn('action4', function ($row) {
                    // Verifica si la boleta es "NO GENERADO"
                    if ($row->boleta != 'NO GENERADO') {
                        $btn = '<a href="' . route('venta.show', $row->id) . '" target="_blank" data-toggle="tooltip" data-original-title="Descargar" class="btn btn-primary btn-sm"><i class="fas fa-download"></i></a>';
                        return $btn; // Retorna el botón solo si la boleta es "NO GENERADO"
                    } else {
                        return ''; // Retorna un string vacío si no es "NO GENERADO" para no mostrar el botón
                    }
                })
                ->rawColumns(['action2','action3','action4'])
                ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request)

    {
        $productos = Producto::all();
        $ventas = Venta::all();
        $numeroVenta = Venta::count() + 1;
        // Obtener el último número de boleta registrado o empezar con 1
        $ultimoNumeroBoleta = Boleta::max('numero') ?? 0;
        $proximoNumeroBoleta = $ultimoNumeroBoleta + 1;


        // if ($request->ajax()) {
		// 	$data = DB::table('clientes as c')
        //     ->where('c.estadocliente','=',1)
		// 	->select('c.*')
		// 	->get();
        //     return DataTables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('action1', function ($row) {
        //             $btn = '<a data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm seleccionarcliente" ><i class="fas fa-check"></i>Seleccionar</a>';
        //             return $btn;
        //         })

        //         ->rawColumns(['action1'])
        //         ->make(true);

        //     $data2 = DB::table('productos as p')
        //     ->where('p.estadoproducto','=',1)
        //     ->select('p.*')
        //     ->get();
        //     return DataTables::of($data2)
        //         ->addIndexColumn()
        //         ->addColumn('action2', function ($row) {
        //             $btn = '<a data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm seleccionarproducto" ><i class="fas fa-check"></i>Seleccionar</a>';
        //             return $btn;
        //         })

        //         ->rawColumns(['action2'])
        //         ->make(true);
        // }

        return view('ventas.create', compact('ventas', 'numeroVenta','productos','proximoNumeroBoleta'));
    }

    public function cargarClientes(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('clientes as c')
                ->where('c.estadocliente', '=', 1)
                ->select('c.*')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    return '<a data-toggle="tooltip" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm seleccionarcliente"><i class="fas fa-check"></i>Seleccionar</a>';
                })
                ->rawColumns(['action1'])
                ->make(true);
        }
    }

    public function cargarProductos(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('productos as p')
                ->where('p.estadoproducto', '=', 1)
                ->select('p.*')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action2', function ($row) {
                    return '<a data-toggle="tooltip" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm seleccionarproducto"><i class="fas fa-check"></i>Seleccionar</a>';
                })
                ->rawColumns(['action2'])
                ->make(true);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $ventas = Venta::all();
        // return redirect()->route('venta.index')->with('datos2', 'Venta registrada correctamente.');
        // Validar la solicitud
        $request->validate([
            'total' => 'required|numeric',
            'pago' => 'required|numeric',
            'vuelto' => 'required|numeric',
            'cliente' => 'required|string',
            'detalles' => 'required|array',
            'proximoNumeroBoleta' => 'required|numeric',
        ]);

        $venta = new Venta(); // Asegúrate de tener el modelo correspondiente
        $venta->total_pagar = $request->total;
        //$venta->pago = $request->pago;
        //$venta->vuelto = $request->vuelto;
        $venta->id_cliente = $request->cliente;
        $venta->idusuario=  auth()->user()->id;
        date_default_timezone_set('America/Lima');
            $fecha_actual = date("Y-m-d H:i:s");
        $venta->created_at = $fecha_actual;
        $venta->updated_at = $fecha_actual;
        $venta->estadoventa = 1;

        $venta->save();

        // Crear el registro de boleta asociado a la venta
        Boleta::create([
            'serie' => 'B001',
            'numero' => $request->proximoNumeroBoleta, // Próximo número de boleta calculado
            'venta_id' => $venta->id,
            'monto'  => $request->total,
            'pago'  => $request->pago,
            'estadoboleta' => 1
        ]);

        // Guardar los detalles de la venta
        foreach ($request->detalles as $detalle) {

            // Buscar el producto por el código y obtener su ID
            $producto = Producto::where('codigo', $detalle['codigo'])->first();

            // Verificar si el producto existe
            if ($producto) {

                $detalleVenta = new DetalleVenta();
                $detalleVenta->id_venta = $venta->id;
                $detalleVenta->id_producto = $producto->id; // Guardar el ID del producto en lugar del código
                $detalleVenta->cantidad = $detalle['cantidad'];
                $detalleVenta->preciopoducto = $detalle['precioVenta'];
                $detalleVenta->preciocompraproducto = $producto->precio_compra;
                date_default_timezone_set('America/Lima');
                    $fecha_actual = date("Y-m-d H:i:s");
                $detalleVenta->created_at = $fecha_actual;
                $detalleVenta->updated_at = $fecha_actual;

                $detalleVenta->save();

                Producto::DisminuirStockProducto($producto->id,$detalle['cantidad']);

            } else {
                // Manejar el caso donde el producto no exista
                return response()->json([
                    'success' => false,
                    'message' => 'Producto con código ' . $detalle['codigo'] . ' no encontrado'
                ], 404);
            }
        }

        // Retornar una respuesta JSON
        //return response()->json(['success' => true]);

        //return redirect()->route('venta.boleta',$venta->id)->with('datos','Venta exitosa ...!');
        return response()->json(['success' => true, 'message' => 'Venta guardada exitosamente!', 'redirect' => route('venta.boleta', $venta->id)]);



    }

    public function verproductoseleccionado(string $id)
    {
        $producto = Producto::find($id);

        return response()->json(['data' => $producto]);
    }
    /**
     * Display the specified resource.
     */
    public function boleta(string $id)
    {


        $idventa = Venta::find($id);

        return view('ventas.boleta',compact('idventa'));
    }

    public function show(string $id)
    {

        //$venta = Venta::find($id);
        $imprimir=true;

        // Obtén la venta y carga sus detalles asociados
        //$venta = Venta::with('detalles')->findOrFail($id);
        // Obtén la venta y carga sus detalles y boleta asociados
        $venta = Venta::with(['detalles', 'boleta'])->findOrFail($id);
        $cliente=Cliente::find($venta->id_cliente);
        $user = User::find($venta->idusuario);
        // Estructura los datos para incluir detalles específicos de la venta y sus productos
        $datosQR = [
            'id' => $venta->id,
            'total_pagar' => $venta->total_pagar,
            'cliente_id' => $venta->id_cliente,
            //'fecha' => $venta->created_at->format('Y-m-d H:i:s'),
            'fecha' => Carbon::parse($venta->created_at)->format('Y-m-d H:i:s'),
            'boleta' => [
                'serie' => $venta->boleta->serie,
                'numero' => $venta->boleta->numero,
            ],

            'detalles' => $venta->detalles->map(function ($detalle) {
                return [
                    'producto_id' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad,
                    'precio_producto' => $detalle->preciopoducto,
                ];
            })
        ];

        // Generar el código QR con los datos del comprobante
        $codigoQR = QrCode::size(200)->generate(json_encode($datosQR));
        //dd($codigoQR);
        return view('ventas.comprobante',compact('venta','imprimir','codigoQR','cliente','user'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::find($id);

        return response()->json(['data' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function agregarcliente(Request $request)
    {
        try {
        // Validar los datos
        $request->validate([
            'ag_nombre_cliente' => 'required|string|max:255',
            'ag_dni_ruc' => 'required|numeric',
        ]);


        // Crear un nuevo cliente
        Cliente::create([
            'nombre_cliente' => $request->ag_nombre_cliente,
            'dni_ruc' => $request->ag_dni_ruc,
            'telefono' => $request->ag_telefono?: '',
            'email' => $request->ag_email?: '',
            'estadocliente' => 1,
        ]);

        return response()->json(['success' => 'Cliente Registrado Exitosamente!']);

        } catch (Exception $e) {
            Log::error('Error al registrar cliente: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servidor. No se pudo registrar el cliente.'. $e->getMessage()], 500);
        }

    }

    public function show2(string $id)
    {
        $venta = Venta::findOrFail($id);
        $boleta=Boleta::where('venta_id', $id)->first();
        $detalles = DB::table('venta_detalle as d')
        ->join('productos as p', 'd.id_producto', '=', 'p.id')
        ->where('d.id_venta', $id)
        ->select(
            'p.codigo as codigo',
            'p.nombre_producto as producto',
            'd.cantidad',
            'd.preciopoducto',
            DB::raw('d.cantidad * d.preciopoducto as total')
        )
        ->get();
        $cliente=Cliente::find($venta->id_cliente);
        $user = User::find($venta->idusuario);

        return response()->json(['data' => $detalles,'data2' => $cliente,'data3' => $user,'data4' =>$boleta,'data5'=>$venta]);
    }



    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $venta = Venta::find($id);
        $venta->estadoventa = 0;

        $detalles = DetalleVenta::where('id_venta','=',$id)->get();
        $boleta = Boleta::where('venta_id','=',$id)->first();

        if ($boleta) {
            $boleta->estadoboleta = 0;
            $boleta->save();
        }

        
        // Guardar los detalles de la venta
        foreach ($detalles as $detalle) {

            // Buscar el producto por el código y obtener su ID
            $producto = Producto::find($detalle->id_producto);

            // Verificar si el producto existe
            if ($producto) {

                
                date_default_timezone_set('America/Lima');
                    $fecha_actual = date("Y-m-d H:i:s");
                $detalle->updated_at = $fecha_actual;

                $detalle->save();

                Producto::AumentarStockProducto($producto->id,$detalle->cantidad);

            } else {
                // Manejar el caso donde el producto no exista
                return response()->json([
                    'success' => false,
                    'message' => 'Producto con código ' . $detalle->codigo . ' no encontrado'
                ], 404);
            }
        }


        $venta->save();

        return response()->json(['success' => 'Venta Eliminada Exitosamente.']);
    }
}
