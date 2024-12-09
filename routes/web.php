<?php

use App\Exports\StockExport;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermisoControl;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\OrdenDetalleController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PrediccionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TempoCompraController;
use App\Http\Controllers\TempoOrdenController;
use App\Http\Controllers\VentaController;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Orden;
use App\Models\ordenDetalle;
use App\Models\tempoCompra;
use App\Models\Venta;

Route::get('/', [HomeController::class,'inicio'])->name('inicio');
Route::get('/logueo', [UserController::class,'showlogin'])->name('login');

Route::post('/salir', [UserController::class,'salir'])->name('logout');

Route::get('/Home', [HomeController::class,'index'])->name('home')->Middleware('auth');

// Route::get('/home', [HomeController::class, 'home'])->name('home');




Route::post('/identificacion', [UserController::class,'verificarlogin'])->name('identificacion');

Route::resource('usuario', UserController::class);


Route::resource('role', RoleController::class);

Route::resource('permiso', PermisoController::class);

Route::get('/cancelarusuario',function(){
    return redirect()->route('usuario.index')->with('datos','Acción Cancelada...!');
  })->name('usuario.cancelar');

Route::resource('cliente', ClienteController::class);
Route::resource('categoria', CategoriaController::class);
Route::resource('producto', ProductoController::class);

Route::resource('venta', VentaController::class);
Route::get('/cargar-clientes', [VentaController::class, 'cargarClientes'])->name('cargar.clientes');
Route::get('/cargar-productos', [VentaController::class, 'cargarProductos'])->name('cargar.productos');

Route::get('/productoseleccionado/{producto}', [VentaController::class, 'verproductoseleccionado'])->name('venta.verproductoseleccionado');
Route::get('/boleta/{id}',[VentaController::class,'boleta'])->name('venta.boleta');
Route::post('agregarcliente', [VentaController::class, 'agregarcliente'])->name('venta.cliente');
Route::get('/verventa/{id}',[VentaController::class,'show2'])->name('venta.show2');


Route::resource('perfil', PerfilController::class);
Route::get('/contraseña', [PerfilController::class,'contraseña'])->name('perfil.contraseña');
Route::post('/contraseña/cambiar', [PerfilController::class,'cambiarcontraseña'])->name('perfil.cambiarcontraseña');
Route::resource('proveedor', ProveedorController::class);

Route::resource('reporte', ReporteController::class);
Route::get('/reporteStock', [ReporteController::class,'reporteStock'])->name('reporte.stock');
Route::get('/reporteVentaDetallada', [ReporteController::class,'reporteVentaDetallada'])->name('reporte.ventaDetallada');

//excel
 Route::get('/exportar-reportestock', function () {
  return Excel::download(new StockExport, 'ReporteStock.xlsx');
})->name('exportar.reporteStock');

//pdf
Route::post('/exportar-pdf', [ReporteController::class, 'exportarPDF'])->name('exportar.pdf');
Route::post('/exportarDinamico-pdf', [ReporteController::class, 'exportarDinamicoPDF'])->name('exportarDinamico.pdf');


//reporte producto
Route::get('/reporteProducto', [ReporteController::class,'reporteProducto'])->name('reporte.producto');
Route::get('/reporte/reporte-productografico', [ReporteController::class, 'reporteProductoGrafico'])->name('reporte.grafico');
Route::get('/reporte/generar-grafico', [ReporteController::class, 'generarGrafico'])->name('reporte.generarGrafico');


//compras
Route::get('compras', [CompraController::class, 'index'])->name('compra.index');
Route::get('compras/create', [CompraController::class, 'create'])->name('compra.create');
Route::post('compras', [CompraController::class, 'store'])->name('compra.store');
Route::post('tempo', [TempoCompraController::class, 'tempo_Compra'])->name('compra.tempo');
Route::delete('tempo/{id}', [TempoCompraController::class, 'destroy'])->name('compra.tempo.destroy');

//Orden de compras
Route::get('orden', [OrdenController::class, 'index'])->name('orden.index');
//muestra el formulario
Route::get('orden/create', [OrdenController::class, 'create'])->name('orden.create');
//envia el formulario
Route::post('orden', [OrdenController::class, 'store'])->name('orden.store');
//envia tabla temporal
Route::post('tempo-orden', [TempoOrdenController::class, 'tempo_Orden'])->name('orden.tempo');
//elimina producto de la tabla temporal
Route::delete('tempo-orden/{id}', [TempoOrdenController::class, 'destroy'])->name('orden.tempo.destroy');
//detalle de la orden detalle
Route::post('detalle/create', [OrdenDetalleController::class, 'store'])->name('orden.detalle.store');
//genera pdf de orden de compra
Route::get('/orden/{id}/descargar-pdf', [OrdenController::class, 'descargarPDF'])->name('orden.descargarPDF');






Route::get('compras/{id}', [CompraController::class, 'show'])->name('compra.show');
Route::get('compras/{id}/edit', [CompraController::class, 'edit'])->name('compra.edit');
Route::put('compras/{id}', [CompraController::class, 'update'])->name('compra.update');

Route::delete('compras/{id}', [CompraController::class, 'destroy'])->name('compra.destroy');

Route::delete('detalle/{id}', [DetalleCompraController::class, 'destroy'])->name('compra.detalle.destroy');
Route::post('detalle/create', [DetalleCompraController::class, 'store'])->name('compra.detalle.store');



//Prediccion
Route::get('/prediccion', [PrediccionController::class, 'index'])->name('prediccion.index');
Route::get('/api/ventas', [VentaController::class, 'obtenerDatosVentas']);

//Ayuda del módulo de ventas

/* Route::get('/abrir-ayuda', function () {
    $ruta = public_path('ayuda/Ayuda_ventas.chm');  // Ruta completa al archivo de ayuda

    if (file_exists($ruta)) {
        // Comando ajustado para abrir el archivo en segundo plano
        pclose(popen('start /B "Ayuda" "' . $ruta . '"', 'r'));
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Archivo no encontrado'], 404);
})->name('abrirAyuda'); */


Route::get('/ayuda_mod_ventas', function () {
    return redirect(asset('ayuda_mod_ventas/index.htm'));
});









