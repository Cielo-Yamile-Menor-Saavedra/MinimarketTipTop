@extends('layout.plantilla')
@section('titulo','Venta')
@section('contenido')

<div class="container ">
    {{-- Mensaje de alerta --}}
    <div id="mensaje2">
        @if (session('datos2'))
            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                {{session('datos2')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">$times;</span>
                </button>
            </div>
        @endif
    </div>

<p>Presione F1 para la ayuda en línea</p>

    <form action="{{--route('venta.store')--}}" method="post">
        @csrf
        <div class="row form-group">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body row">
                                <div class="row">
                                    <div class="col-7">
                                        <label class="control-label">Boleta:</label>
                                    </div>
                                    <div class="col-5">
                                        <input class="form-control " type="text" id="boleta" name="boleta" value="B001 - {{ $proximoNumeroBoleta }}" readonly/>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body row">
                                <h5 class="card-title">Datos del cliente</h5>
                                {{-- <p class="card-text"></p> --}}
                                <div class="col-7">
                                    <label class="control-label">Cliente:</label>
                                    <div class="row">
                                        <div class="col-7">

                                            <a href="" name="buscarcliente" type="button" id="buscarcliente" class="btn btn-primary"><i class="fas fa-search"></i>cliente</a>
                                        </div>
                                        <div class="col-5">
                                            <a href="" name="añadircliente" type="button" id="añadircliente" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <label class="control-label">codigo:</label>
                                    <input class="form-control " type="text" id="id_cliente" name="id_cliente" readonly="readonly"/>
                                </div>
                                <div class="col-12">
                                    <label class="control-label">Nombres:</label>
                                    <input class="form-control " type="text" id="nombre_cliente" name="nombre_cliente" readonly="readonly"/>
                                </div>
                                <div class="col-12">
                                    <label class="control-label">Email:</label>
                                    <input class="form-control " type="email" id="email" name="email" readonly="readonly"/>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body row">
                                <h5 class="card-title">Datos de producto</h5>

                                <div class="col-5">
                                    <label class="control-label">Producto:</label><br>
                                    <a href="" name="buscarproducto" type="button" id="buscarproducto" class="btn btn-primary"><i class="fas fa-search"></i>producto</a>
                                </div>
                                <div class="col-7">
                                    <label class="control-label">codigo:</label>
                                    <input class="form-control " type="text" id="codigo" name="codigo" readonly="readonly"/>
                                </div>
                                <div class="col-10">
                                    <label class="control-label">Nombre:</label>
                                    <input class="form-control " type="text" id="nombre_producto" name="nombre_producto" readonly="readonly"/>
                                </div>
                                <div class="col-2">
                                    <label class="control-label">Stock:</label>
                                    <input class="form-control " type="text" id="stock" name="stock" readonly="readonly"/>
                                </div>
                                <div class="col-4">
                                    <label class="control-label">Cantidad:</label>
                                    <input class="form-control " type="text" id="cantidadproducto" name="cantidadproducto"/>
                                </div>
                                <div class="col-4">
                                    <label class="control-label">Precio venta:</label>
                                    <input class="form-control " type="text" id="precio_venta" name="precio_venta" readonly="readonly"/>
                                </div>
                                <div class="col-4">
                                    <br>
                                    <button type="button" id="btnadddet" class="btn btn-success"><i class="fas fa-shopping-cart"></i>
                                        Agregar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body row">
                        <h5 class="card-title">Productos de carrito</h5>
                        {{-- Mensaje de alerta --}}
                        {{-- <div id="mensaje">
                            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">$times;</span>
                                </button>
                            </div>
                        </div> --}}
                        <div id="error-mensaje" class="alert alert-danger hidden" style="display:none;"></div>

                        <div class="col-md-12 pt-3">
                            <div class="table-responsive">
                                <table id="table-detalles" name="table-detalles" class="table table-striped table-bordered table-condensed table-hover" style='background-color:#FFFFFF;'>
                                    <thead class="thead-default" style="background-color:#3c8dbc;color: #fff;">
                                        <th width="10" class="text-center">Opcion</th>
                                        <th class="text-center">Código</th>
                                        <th>Producto</th>
                                        <th>stock</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Subtotal</th>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Operaciones gravadas : </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control text-right" name="opgravadas" id="opgravadas" readonly="readonly">
                            </div><br><br>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Operaciones exoneradas: </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control text-right" name="opexoneradas" id="opexoneradas" readonly="readonly">
                            </div><br><br>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">IGV: </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control text-right" name="igvtotal" id="igvtotal" readonly="readonly">
                            </div><br><br>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Total venta: </label>
                            </div>
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control text-right" name="totalventa" id="totalventa" readonly="readonly">
                            </div><br><br>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="">Pago: </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" class="form-control text-right" name="pago" id="pago" >
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="">Vuelto: </label>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" class="form-control text-right" name="vuelto" id="vuelto" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-md-12 text-center">
                    <div id="guardar">
                        <div class="form-group">
                            <button class="btn btn-primary" id="btnRegistrar" data-loading-text="<i class='fa a-spinner fa-spin'> </i> Registrando">
                            <i class='fas fa-save'></i> Registrar</button>

                            <a href="{{--route('prestamo.cancelar')--}}" class='btn btn-danger'><i class='fas fa-ban'></i> Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <!-- Modal buscar cliente-->
    <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Clientes</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                <table class="table table-striped nowrap" id="table-clientes" name="table-clientes">
                    <thead style="background-color:#1C91EC;color: #fff;">
                        <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">DNI/RUC</th>
                        <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>

                </table>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal buscar producto-->
    <div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Productos</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                <table class="table table-striped nowrap" id="table-productos" name="table-productos">
                    <thead style="background-color:#1C91EC;color: #fff;">
                        <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Precio venta</th>
                        <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>

                </table>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal buscar producto-->
    <div class="modal fade" id="modalAñadirCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Añadir cliente</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="ClienteForm" name="ClienteForm" >
                    @csrf
                    {{-- <div class="form-group row">
                        <div class="col-12">
                            <label class="control-label">Nombre:</label>
                            <input type="text" name="name" id="name" class="form-control"  placeholder="Nombre" required>

                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nombre_cliente">Nombre<b>*</b></label>
                                <input type="text" name="ag_nombre_cliente" id="ag_nombre_cliente" class="form-control" required>
                                <small style="color: red; display: none" id="lbl_create">*Este campo es requerido</small>
                            </div>
                            <div class="form-group">
                                <label for="dni_ruc">DNI/RUC<b>*</b></label>
                                <input type="number" name="ag_dni_ruc" id="ag_dni_ruc" class="form-control" required>
                                <small style="color: red; display: none" id="lbl_create">*Este campo es requerido</small>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Telefono<b></b></label>
                                <input type="number" name="ag_telefono" id="ag_telefono" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="email">Email<b></b></label>
                                <input type="email" name="ag_email" id="ag_email" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnguardarcliente" class="btn btn-success">
                    Registrar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')

{{-- <script>
    function capturarTecla(event) {
        // Verificar si se presionó la tecla F1
        if (event.key === "F1") {
            event.preventDefault(); // Prevenir la acción predeterminada del navegador

            // Llamar al backend para abrir el archivo de ayuda
            fetch('{{ route("abrirAyuda") }}')
                .then(response => {
                    if (response.ok) {
                        console.log("Ayuda abierta correctamente.");
                    } else {
                        alert("No se pudo abrir el archivo de ayuda.");
                    }
                })
                .catch(error => {
                    console.error("Error al intentar abrir la ayuda:", error);
                });
        }
    }

    // Escuchar eventos de teclado en toda la página
    document.addEventListener("keydown", capturarTecla);
</script>
 --}}

 <script>
    function capturarTecla(event) {
        if (event.key === "F1") {
            event.preventDefault(); // Prevenir la acción predeterminada del navegador
            window.open('/ayuda_mod_ventas/index.htm', '_blank'); // Abrir en una nueva pestaña o ventana
        }
    }
    document.addEventListener("keydown", capturarTecla);
</script>



<script>
    //para cerrar el mensaje
    setTimeout(function () {
        //selecciono el id mensaje y lo remuevo en 2000 segundos
        document.querySelector('#mensaje').remove();

    }, 2000);
</script>
<script>

    $(document).ready(function() {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var tablecli = $('#table-clientes').DataTable({
            responsive: true, // Habilitar la opción responsive
            autoWidth: false,
            searchDelay : 2000,
            processing: true,
            serverSide: true,
            "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nada encontrado - disculpa",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate":{
                "next" : "Siguiente",
                "previous" : "Anterior"
            }
            },

            order: [
                [0, "asc"]
            ],
            ajax: "{{ route('cargar.clientes') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre_cliente',
                    name: 'nombre_cliente'
                },
                {
                    data: 'dni_ruc',
                    name: 'dni_ruc'
                },
                {
                    data: null,
                    name: 'name',
                    'render': function(data, type, row) {
                        // return @can('permiso.show') data.action1 @endcan;
                        @can('permiso.show')
                            return data.action1 ?? ''; // Mostrar acción si tiene permiso
                        @else
                            return ''; // Devolver vacío si no tiene permiso
                        @endcan
                    }
                }
            ]
        });

        $('#buscarcliente').click(function(e) {
            e.preventDefault();

            $('#modalCliente').modal('show');

        });

        $('body').on('click', '.seleccionarcliente', function() {
            var Cliente_id_seleccionar = $(this).data('id');//viene del data-id de la funcion index
            $.get('{{ route('venta.edit', ['ventum' => ':ventum']) }}'.replace(':ventum', Cliente_id_seleccionar),
                function(data) {
                    console.log(data);
                    $('#id_cliente').val(data.data.id);
                    $('#nombre_cliente').val(data.data.nombre_cliente);
                    $('#dni_ruc').val(data.data.dni_ruc);
                    $('#email').val(data.data.email);

                    $('#modalCliente').modal('hide');
                })
        });

        $('#buscarproducto').click(function(e) {
            e.preventDefault();
            $('#modalProducto').modal('show');

        });

        var table = $('#table-productos').DataTable({
            responsive: true, // Habilitar la opción responsive
            autoWidth: false,
            searchDelay : 2000,
            processing: true,
            serverSide: true,
            "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nada encontrado - disculpa",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate":{
                "next" : "Siguiente",
                "previous" : "Anterior"
            }
            },

            order: [
                [0, "asc"]
            ],
            ajax: "{{ route('cargar.productos') }}",
            columns: [{
                    data: 'codigo',
                    name: 'codigo'
                },
                {
                    data: 'nombre_producto',
                    name: 'nombre_producto'
                },
                {
                    data: 'stock',
                    name: 'stock'
                },
                {
                    data: 'precio_venta',
                    name: 'precio_venta'
                },
                {
                    data: null,
                    name: 'name',
                    'render': function(data2, type, row) {
                        //return @can('permiso.show') data2.action2 @endcan;
                        @can('permiso.show')
                            return data2.action2 ?? ''; // Mostrar acción si tiene permiso
                        @else
                            return ''; // Devolver vacío si no tiene permiso
                        @endcan
                    }
                }
            ]
        });

        $('body').on('click', '.seleccionarproducto', function() {
            var Producto_id_seleccionar = $(this).data('id');//viene del data-id de la funcion index
            $.get('{{ route('venta.verproductoseleccionado', ['producto' => ':producto']) }}'.replace(':producto', Producto_id_seleccionar),
                function(data) {
                    console.log(data);
                    $('#codigo').val(data.data.codigo);
                    $('#nombre_producto').val(data.data.nombre_producto);
                    $('#stock').val(data.data.stock);
                    $('#precio_venta').val(data.data.precio_venta);

                    $('#modalProducto').modal('hide');
                })
        });

        var tableDetalles = $('#table-detalles').DataTable({
            paging: true, // Activar la paginación
            searching: false, // Desactivar la búsqueda si no la necesitas
            ordering: false, // Desactivar la ordenación
            info: true, // Mostrar la información de la tabla
            language: {
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "emptyTable": "No hay productos en el carrito",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 productos",
                "lengthMenu": "Mostrar _MENU_ productos"
            }
        });

        function mostrarMensajeError(mensaje) {
            $("#error-mensaje").css('display', 'block');
            $("#error-mensaje").removeClass("hidden");
            $("#error-mensaje").addClass("alert-danger");
            $("#error-mensaje").html("<button type='button' class='close' data-dismiss='alert'>×</button><span><b>Error!</b> " + mensaje + ".</span>");
            $('#error-mensaje').delay(5000).hide(400); // Oculta el mensaje después de 5 segundos
        }
        // Evento para agregar productos a la tabla

        $('#btnadddet').on('click', function() {
            var codigo = $('#codigo').val();
            var nombreProducto = $('#nombre_producto').val();
            var stock = parseInt($("#stock").val());;
            var cantidad = parseInt($("#cantidadproducto").val());
            var precio_venta =parseFloat($('#precio_venta').val());
            var subtotal=cantidad * precio_venta;

            if (codigo && nombreProducto && cantidad) {
                if (cantidad <= 0) {
                    mostrarMensajeError("Por favor, escriba una cantidad correcta ");
                    return false;
                }
                else if (cantidad > stock) {
                    mostrarMensajeError("No se tiene tal cantidad del producto, solo hay " + stock);
                    return false;
                }

                var productoRepetido = false;

                // Recorrer la tabla para buscar si el código ya existe
                $('#table-detalles tbody tr').each(function() {
                    var codigoExistente = $(this).find("td:eq(1)").text(); // Obtener el código de la segunda columna
                    if (codigoExistente === codigo) {
                        productoRepetido = true;
                        return false; // Detener el ciclo si ya se encontró el producto
                    }
                });

                // Si el producto ya existe, mostrar un mensaje y no agregar
                if (productoRepetido) {
                    mostrarMensajeError("Este producto ya está en el carrito.");
                } else {

                    // Agregar fila a la tabla
                    var rowNode = tableDetalles.row.add([
                        '<button class="btn btn-danger btn-sm eliminar-producto"><i class="fas fa-trash-alt"></i></button>',
                        codigo,
                        nombreProducto,
                        stock,
                        //cantidad,
                        '<input type="number" class="form-control cantidad-detalle cantidad-input" value="' + cantidad + '" min="1">',
                        precio_venta.toFixed(2),
                        subtotal.toFixed(2)

                    ]).draw(false).node();

                    actualizarTotal();
                    // Asignar evento change al input de cantidad
                    $(rowNode).find('.cantidad-detalle').on('change', function() {
                        var nuevaCantidad = parseInt($(this).val());
                        if (nuevaCantidad <= 0) {
                            mostrarMensajeError("La cantidad debe ser mayor que cero.");
                            $(this).val(cantidad); // Restaurar la cantidad anterior
                            return;
                        } else if (nuevaCantidad > stock) {
                            mostrarMensajeError("No se tiene tal cantidad del producto, solo hay " + stock);
                            $(this).val(cantidad); // Restaurar la cantidad anterior
                            return;
                        }

                        // Actualizar subtotal
                        var nuevoSubtotal = nuevaCantidad * precio_venta;
                        //tableDetalles.cell($(this).closest('td').next('td')).data(nuevoSubtotal).draw();
                        $(this).closest('tr').find('td:eq(6)').text(nuevoSubtotal.toFixed(2));
                        // Actualizar el total general
                        actualizarTotal();

                    });

                    // Limpiar los campos de entrada
                    $('#codigo').val('');
                    $('#nombre_producto').val('');
                    $('#stock').val('');
                    $('#cantidadproducto').val('');
                    $('#precio_venta').val('');
                }
            } else {
                //alert("Por favor, complete todos los campos antes de agregar.");
                // Mostrar mensaje de error si faltan campos
                mostrarMensajeError("Por favor, complete todos los campos antes de agregar un producto");
                return false;
            }

        });

        // Evento para eliminar una fila de la tabla
        $('#table-detalles tbody').on('click', '.eliminar-producto', function() {
            tableDetalles.row($(this).parents('tr')).remove().draw();
            actualizarTotal();
        });

        $('#table-detalles').on('change', '.cantidad-detalle', function() {
            var nuevaCantidad = $(this).val();
            console.log("Cantidad actualizada: ", nuevaCantidad);
        });

        // // Evento para cambiar la cantidad
        // $('#table-detalles tbody').on('input', '.cantidad-detalle', function() {
        //     // Obtener la fila actual
        //     var $fila = $(this).closest('tr');

        //     // Obtener el precio de venta y la cantidad
        //     var precioVenta = parseFloat($fila.find('.precio-venta').text());
        //     var cantidad = parseInt($(this).val());

        //     // Calcular el nuevo subtotal
        //     var subtotal = precioVenta * cantidad;

        //     // Actualizar el subtotal en la fila
        //     $fila.find('.subtotal').text(subtotal.toFixed(2));

        //     // Aquí puedes actualizar el total general si lo deseas
        //     actualizarTotalGeneral();
        // });
        // Función para recalcular el total general
        function actualizarTotal() {
            var total = 0;
            var preciosinigv=0;//precio unitario sin igv de producto
            var preciototalsinigv=0; //precio total de la cantidad de un producto sin igv
            var subtotalsinigv=0; //op.gravadas
            var opexoneradas=0.00;

            var igvproducto=0;//igv de producto incluida la cantidad
            var igvtotal=0;// igv total

            // Recorrer todas las filas de la tabla y sumar los subtotales
            $('#table-detalles tbody tr').each(function() {
                var subtotal = parseFloat($(this).find('td:eq(6)').text());
                var cantidad = parseFloat($(this).find('input.cantidad-input').val());
                total += subtotal;

                var precioventaunidad=parseFloat($(this).find('td:eq(5)').text());
                preciosinigv = precioventaunidad/1.18;
                preciototalsinigv=preciosinigv*cantidad;
                subtotalsinigv +=preciototalsinigv;


                //actualizar igv
                igvproducto = subtotal-preciototalsinigv;
                igvtotal += igvproducto;

                preciosinigv =0;
                preciototalsinigv=0;
                igvproducto=0;

            });

            if (isNaN(total)) {
                    total = 0;
                }
            if (isNaN(subtotalsinigv)) {
                subtotalsinigv = 0;
            }
            if (isNaN(igvtotal)) {
                igvtotal = 0;
            }
            if (isNaN(opexoneradas)) {
                opexoneradas = 0;
            }

            // Actualizar el total en el campo correspondiente
            $('#totalventa').val(total.toFixed(2));
            $('#opgravadas').val(subtotalsinigv.toFixed(2));
            $('#igvtotal').val(igvtotal.toFixed(2));
            $('#opexoneradas').val(opexoneradas.toFixed(2));

            actualizarVuelto();
        }

        // Nueva función para calcular y actualizar el vuelto
        function actualizarVuelto() {
            // Obtener el total de la venta y el pago ingresado por el cliente
            var total = parseFloat($('#totalventa').val());
            var pago = parseFloat($('#pago').val());

            // Si el pago es NaN, asumir que es 0
            if (isNaN(pago)) {
                pago = 0;
            }

            // Calcular el vuelto
            var vuelto = pago - total;

            // Si el vuelto es negativo, mostrar 0
            if (vuelto < 0) {
                vuelto = 0;
            }

            // Actualizar el campo de vuelto
            $('#vuelto').val(vuelto.toFixed(2));
        }

        // Evento para calcular el vuelto
        $('#pago').on('input', function() {
            actualizarVuelto();
        });

        $('#btnRegistrar').on('click', function() {
            // Cambiar el texto del botón mientras se realiza el registro
            var $btn = $(this);
            $btn.html($btn.data('loading-text')).attr('disabled', true);

            // Recopilar los datos generales
            var total = $('#totalventa').val();
            var pago = $('#pago').val();
            var vuelto = $('#vuelto').val();

            var cliente = $('#id_cliente').val();

            var proximoNumeroBoleta= $('#boleta').val().split(" - ")[1];
            // Verificar si se han ingresado todos los datos requeridos
            if (!total || !pago || !vuelto || !cliente) {
                // alert('Por favor, complete todos los campos');
                // $btn.html('<i class="fas fa-save"></i> Registrar').attr('disabled', false);
                Toast.fire({
                    icon: 'error',
                    title: 'Por favor, complete todos los campos'
                });
                $btn.html('<i class="fas fa-save"></i> Registrar').attr('disabled', false);
                return false;
            }

            // Recopilar los detalles de los productos
            var detalles = [];
            $('#table-detalles tbody tr').each(function() {
                var codigo = $(this).find('td:eq(1)').text();
                var nombreProducto = $(this).find('td:eq(2)').text();
                var cantidad = $(this).find('td:eq(4)').find('input').val(); // Obtener la cantidad
                var precioVenta = $(this).find('td:eq(5)').text();
                var subtotal = $(this).find('td:eq(6)').text();

                detalles.push({
                    codigo: codigo,
                    nombreProducto: nombreProducto,
                    cantidad: cantidad,
                    precioVenta: precioVenta,
                    subtotal: subtotal
                });
            });

            // Crear un objeto con todos los datos para enviar
            var ventaData = {
                total: total,
                pago: pago,
                vuelto: vuelto,
                cliente: cliente,
                detalles: detalles,
                proximoNumeroBoleta: proximoNumeroBoleta
            };

            // Enviar los datos al servidor utilizando AJAX
            $.ajax({
                url: "{{route('venta.store')}}",  // URL de la ruta que maneja la petición en tu backend
                type: 'POST',
                data: JSON.stringify(ventaData), // Convertir los datos a formato JSON
                contentType: 'application/json',
                // success: function(response) {
                //     // Manejar la respuesta del servidor
                //     if (response.success) {
                //         alert('Venta guardada exitosamente');
                //         // Puedes limpiar el formulario o redirigir a otra página
                //         window.location.reload();  // Recargar la página o redirigir
                //     } else {
                //         alert('Hubo un problema al guardar la venta');
                //     }
                //     $btn.html('<i class="fas fa-save"></i> Registrar').attr('disabled', false);
                // },
                success: function(response) {
                    // Manejar la respuesta del servidor
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Venta guardada exitosamente'
                        });
                        // Puedes limpiar el formulario o redirigir a otra página
                        //window.location.reload();  // Recargar la página o redirigir
                        // Redirigir a la página de la boleta después de un pequeño retraso para que el usuario vea el mensaje
                        setTimeout(function() {
                            window.location.href = response.redirect; // Redirigir a la URL especificada en la respuesta
                        }, 2000); // 2000 ms = 2 segundos
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Hubo un problema al guardar la venta'+response.message
                        });
                    }
                    $btn.html('<i class="fas fa-save"></i> Registrar').attr('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error al guardar la venta:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al guardar la venta'+xhr.responseText
                    });
                    $btn.html('<i class="fas fa-save"></i> Registrar').attr('disabled', false);
                }
            });
        });

        $('#añadircliente').click(function(e) {
            e.preventDefault();
            $('#modalAñadirCliente').modal('show');

        });

        $('#btnguardarcliente').click(function(e) {
            e.preventDefault();
            ag_nombre_cliente = $("#ag_nombre_cliente").val();
            ag_dni_ruc = $("#ag_dni_ruc").val();
            ag_telefono = $("#ag_telefono").val();
            ag_email = $("#ag_email").val();
            if (ag_nombre_cliente == '' || ag_dni_ruc == '' || ag_telefono=='' || ag_email=='') {
                Toast.fire({
                        type: 'error',
                        title: 'Complete los campos requeridos por favor'
                    })
                return false;
            }
            $.ajax({
                data: $('#ClienteForm').serialize(),
                url: "{{ route('venta.cliente') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    console.log('Success:', data);
                    Toast.fire({
                        type: 'success',
                        title: data.success
                    })
                    $('#ClienteForm').trigger("reset");
                    $('#modalAñadirCliente').modal('hide');
                    // Recargar la tabla DataTable en el modal de buscar cliente
                    tablecli.draw();
                },
                // error: function(data) {
                //     console.log('Error:', data);
                //     Toast.fire({
                //         type: 'error',
                //         title: 'Cliente fallo al Registrarse.'
                //     })
                // }
                error: function(xhr, status, error) {
                    console.error('Error:', xhr); // Muestra toda la respuesta para ver detalles
                    console.error('Status:', status);
                    console.error('Error Message:', error);

                    // Si el servidor responde con un mensaje de error en JSON
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON.error // Muestra el mensaje de error específico del controlador
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Cliente falló al registrarse.'
                        });
                    }
                }
            });
        });

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

@endsection
