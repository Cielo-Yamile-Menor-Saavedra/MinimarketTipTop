@extends('layout.plantilla')
@section('titulo','Ventas')


@section('contenido')

<div class="container ">
    <div id="mensaje">
        @if (session('datos'))
            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                {{session('datos')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">$times;</span>
                </button>
            </div>
        @endif
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">LISTA DE VENTAS</h5>
              <p class="card-text"></p>
              <style>
                .table-containerr {
                    overflow-x: auto;
                    overflow-y: hidden; /* Opcional, para ocultar el desplazamiento vertical */
                    white-space: nowrap;
                    max-width: 100%; /* Limita el ancho para adaptarse a la pantalla */
                }
            </style>
            <div class="table-containerr">
              <table class="table table-striped nowrap" id="table-ventas" name="table-ventas">
                <thead style="background-color:#1C91EC;color: #fff;">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Boleta</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Total</th>
                  <th scope="col">N° productos</th>
                  <th scope="col">Opciones</th>
                </tr>
                </thead>

                <tbody>

                </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Modal Ver detalles-->
    <div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Venta</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <p class="col-2">Boleta: </p>
                    <p class="col-3" id="v_boleta"></p>
                    <p class="col-2">Cliente:</p>
                    <p class="col-5" id="v_nombre_cliente">c</p>
                </div>
                <div class="row">

                    <p class="col-2">DNI/RUC:</p>
                    <p class="col-3" id="v_dni_ruc"></p>
                    <p class="col-2">Email:</p>
                    <p class="col-5" id="v_email"></p>

                </div>

                <table class="table table-striped nowrap" id="table-detalles" name="table-detalles">
                    <thead style="background-color:#1C91EC;color: #fff;">
                        <tr>
                        <th scope="col">COD.</th>
                        <th scope="col">PRODUCTO</th>
                        <th scope="col">CANT.</th>
                        <th scope="col">P.U.</th>
                        <th scope="col">TOTAL</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>

                </table>

                <div class="row">
                    <div class="col-md-5">
                        <label for="">Operaciones gravadas : </label>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <p class=" text-right" name="opgravadas" id="opgravadas"></p>
                    </div><br><br>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label for="">Operaciones exoneradas: </label>
                    </div>

                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <p class="text-right" name="opexoneradas" id="opexoneradas" >0.00</p>
                    </div><br><br>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label for="">IGV: </label>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <p class="text-right" name="igvtotal" id="igvtotal" ></p>
                    </div><br><br>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label for="">Total venta: </label>
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <p class="text-right" name="totalventa" id="totalventa" ></p>
                    </div><br><br>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Pago: </label>
                            </div>

                            {{-- <div class="col-md-4">
                                <input type="text" class="form-control text-right" name="pago" id="pago" >
                            </div> --}}
                            <div class="col-md-3">
                                <p class="text-right" name="pago" id="pago" ></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Vuelto: </label>
                            </div>

                            {{-- <div class="col-md-4">
                                <p class="text-right" name="vuelto" id="vuelto" ></p>
                            </div> --}}
                            <div class="col-md-3">
                                <p class="text-right" name="vuelto" id="vuelto" ></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Cód.: </label>
                            </div>

                            {{-- <div class="col-md-4">
                                <input type="text" class="form-control text-right" name="pago" id="pago" >
                            </div> --}}
                            <div class="col-md-3">
                                <p class="text-right" name="cod_vendedor" id="cod_vendedor" ></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Vendedor: </label>
                            </div>

                            {{-- <div class="col-md-4">
                                <p class="text-right" name="vuelto" id="vuelto" ></p>
                            </div> --}}
                            <div class="col-md-8">
                                <p class="text-right" name="nombre_vendedor" id="nombre_vendedor" ></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
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
        var table = $('#table-ventas').DataTable({
            responsive: true, // Habilitar la opción responsive
            autoWidth: false,
            searchDelay : 2000,
            processing: true,
            serverSide: true,
            scrollX: true, // Activa el desplazamiento horizontal
            scroller: true, // Usa el plugin Scroller para manejar el desplazamiento en tablas grandes
            "language": {
            "lengthMenu": "Mostrar MENU registros por página",
            "zeroRecords": "Nada encontrado - disculpa",
            "info": "Mostrando la página PAGE de PAGES",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de MAX registros totales)",
            "search": "Buscar:",
            "paginate":{
                "next" : "Siguiente",
                "previous" : "Anterior"
            }
            },

            order: [
                [0, "asc"]
            ],
            ajax: "{{ route('venta.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'boleta',
                    name: 'boleta'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },
                {
                    data: 'nombre_cliente',
                    name: 'nombre_cliente'
                },

                {
                    data: 'total_pagar',
                    name: 'total_pagar'
                },
                {
                    data: 'total_cantidad',
                    name: 'total_cantidad'
                },
                {
                    data: null,
                    name: 'name',
                    'render': function(data, type, row) {
                        return @can('usuario.show') data.action3 +' '+ @endcan ''

                            @can('usuario.destroy') +data.action2 @endcan;
                    }
                }
            ]
        });

        $('body').on('click', '.eyeVenta', function() {
            var Venta_id_ver = $(this).data('id');
            $('#modalVerDetalle').modal('show');
            $.get('{{ route('venta.show2', ['id' => ':id']) }}'.replace(':id', Venta_id_ver),
                function(data) {
                    console.log(data);
                    $('#v_boleta').text(data.data4.serie + '-' + data.data4.numero);
                    $('#v_nombre_cliente').text(data.data2.nombre_cliente);
                    $('#v_dni_ruc').text(data.data2.dni_ruc);
                    $('#v_email').text(data.data2.email);
                    $('#totalventa').text(data.data5.total_pagar);

                    var totalPagar = parseFloat(data.data5.total_pagar);
                    var opgravadas = totalPagar / 1.18;  // Calculando opgravadas
                    var igv= totalPagar - opgravadas;
                    var pago=parseFloat(data.data4.pago);
                    var vuelto = pago - totalPagar;

                    $('#opgravadas').text(opgravadas.toFixed(2));
                    $('#igvtotal').text(igv.toFixed(2));
                    $('#pago').text(pago.toFixed(2));
                    $('#vuelto').text(vuelto.toFixed(2));

                    $('#nombre_vendedor').text(data.data3.email);
                    $('#cod_vendedor').text(data.data3.id);

                    tableDetalles.clear();

                    // Iterar sobre los detalles y agregarlos a la tabla
                    data.data.forEach(function(detalle) {
                        console.log(detalle);
                        // //var total = detalle.preciopoducto * detalle.cantidad;
                        // var fila = `
                        //     <tr>
                        //         <td>${detalle.codigo}</td>
                        //         <td>${detalle.producto}</td>
                        //         <td>${detalle.cantidad}</td>
                        //         <td>${parseFloat(detalle.preciopoducto).toFixed(2)}</td>
                        //         <td>${parseFloat(detalle.total).toFixed(2)}</td>
                        //     </tr>
                        // `;
                        // $('#table-detalles tbody').append(fila);
                        tableDetalles.row.add([
                            detalle.codigo,
                            detalle.producto,
                            detalle.cantidad,
                            parseFloat(detalle.preciopoducto).toFixed(2),
                            parseFloat(detalle.total).toFixed(2)
                        ]);
                    });
                    tableDetalles.draw();

                })

        });
        var tableDetalles = $('#table-detalles').DataTable({
            paging: true, // Activar la paginación
            searching: false, // Desactivar la búsqueda si no la necesitas
            ordering: false, // Desactivar la ordenación
            info: true, // Mostrar la información de la tabla
            responsive: true, // Activa la responsividad

            language: {
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "emptyTable": "No hay detalles de venta",
                "info": "Mostrando START a END de TOTAL productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 productos",
                "lengthMenu": "Mostrar MENU productos"
            }
        });

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

@endsection
