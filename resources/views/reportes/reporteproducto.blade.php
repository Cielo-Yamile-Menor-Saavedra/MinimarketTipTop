@extends('layout.plantilla')

@section('titulo','Rporte-productos')

@section('contenido')

<div class="container ">

    <div>
        <form id="formFiltros">
            <div class="row">
            
                <div class="col-md-1"><label for="producto_id">Producto:</label>
                    </div>
                <div class="col-md-9">
                    <select id="producto_id" class="form-select" name="producto_id">
                        <option value="" selected >- Seleccione producto -</option>
                        @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre_producto }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

            <div class="row my-2">
                <div class="col-md-1"><label for="fecha_inicio">Desde:</label></div>
                <div class="col-md-3"><input type="date" class="form-control me-2" id="fecha_inicio" name="fecha_inicio" value="{{ $fecha_inicio }}" required></div>
                <div class="col-md-1"><label for="fecha_fin">Hasta:</label></div>
                <div class="col-md-3"><input type="date" class="form-control me-2" id="fecha_fin" name="fecha_fin" value="{{ $fecha_fin }}" required></div>
                <div class="col-md-1">
                    <label for="agrupacion">Agrupar:</label>
                </div>
                <div class="col-md-3">
                    <select id="agrupacion" class="form-select" name="agrupacion">
                        {{-- <option value="">- Seleccione -</option> --}}
                        <option value="dia">Día</option>
                        <option value="semana">Semana</option>
                        <option value="mes" selected>Mes</option>
                        <option value="anio">Anual</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label for="tipo_grafico">Tipo de gráfico:</label>
                </div>
                <div class="col-md-4">
                    
                    <select id="tipo_grafico" class="form-select" name="tipo_grafico">
                        {{-- <option value="" selected>- Seleccione tipo -</option> --}}
                        <option value="bar" selected>Barras</option>
                        <option value="line">Líneas</option>
                        <option value="pie">Pastel</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-success" type="button" id="btnFiltrar" name="btnFiltrar">Filtrar</button>
                </div>
            </div>
        </form>
       <div class="row">
        <div class="col-md-2">
            <form id="exportPdfForm" action="{{ route('exportarDinamico.pdf') }}" method="POST">
                @csrf
                <input type="hidden" name="chart" id="chartInput">
                <input type="" name="producto_id_dinamico" id="producto_id_dinamico" hidden>
                <input type="date" name="fecha_inicio_dinamico" id="fecha_inicio_dinamico" hidden>
                <input type="date" name="fecha_fin_dinamico" id="fecha_fin_dinamico" hidden >

                <button type="submit" class="btn btn-danger">Exportar PDF</button>
            </form>
        </div>
       </div>

    </div>
    
    {{-- <div id="grafico-container" class="mt-5">
        <canvas id="grafico"></canvas>
    </div> --}}
    <br>
    <div class="row">
        <div class="col-md-7">
            <style>
                .table-containerr {
                    overflow-x: auto;
                    overflow-y: hidden; /* Opcional, para ocultar el desplazamiento vertical */
                    white-space: nowrap;
                    max-width: 100%; /* Limita el ancho para adaptarse a la pantalla */
                }
            </style>
            <div class="table-containerr">
                <table class="table table-striped nowrap" id="table-ventasxproductos" name="table-ventasxproductos">
                  <thead style="background-color:#1C91EC;color: #fff;">
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
          
                  </tbody>
          
                  </table>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-success" style="width: 100%">
                <div class="card-header">
                  <h3 class="card-title">Desempeño de ventas</h3>
                  
        
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart">
                    <canvas id="graficoVentas" style="height: 400px;"></canvas>
        
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart">
                  </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    
    
  
    
</div>
@endsection

@section('script')
{{-- <script>
    //para cerrar el mensaje
    setTimeout(function () {
        //selecciono el id mensaje y lo remuevo en 2000 segundos
        document.querySelector('#mensaje').remove();
        
    }, 6000);
</script> --}}
<script>
    // $(document).ready(function() {

    //     $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //     });
    //     const Toast = Swal.mixin({
    //         toast: true,
    //         position: 'top-end',
    //         showConfirmButton: false,
    //         timer: 3000
    //     });
    //     var table = $('#table-ventasxproductos').DataTable({
    //         responsive: true, // Habilitar la opción responsive
    //         autoWidth: false,
    //         searchDelay : 2000,
    //         processing: true,
    //         serverSide: true,
    //         scrollX: true, // Activa el desplazamiento horizontal
    //         scroller: true, // Usa el plugin Scroller para manejar el desplazamiento en tablas grandes
    //         "language": {
    //         "lengthMenu": "Mostrar _MENU_ registros por página", // Asegúrate de usar _MENU_
    //         "zeroRecords": "Nada encontrado - disculpa",
    //         "info": "Mostrando la página _PAGE_ de _PAGES_", // Usa _PAGE_ y _PAGES_ para interpolar correctamente
    //         "infoEmpty": "No hay registros disponibles",
    //         "infoFiltered": "(filtrado de _MAX_ registros totales)",
    //         "search": "Buscar:",
    //         "paginate":{
    //             "next" : "Siguiente",
    //             "previous" : "Anterior"
    //         }
    //         },

    //         order: [
    //             [0, "asc"]
    //         ],
    //         //ajax: "{{ route('reporte.ventaDetallada') }}",
    //         ajax: {
    //             url: "{{ route('reporte.producto') }}",
    //             type: "GET",
    //             data: function(d) {
    //                 // Añadir las fechas del formulario a la solicitud AJAX
    //                 d.fecha_inicio = $('#fecha_inicio').val();
    //                 d.fecha_fin = $('#fecha_fin').val();
    //                 d.producto_id = $('#producto_id').val();
    //                 // d.agrupacion = $('#agrupacion').val();
    //                 // d.tipo_grafico = $('#tipo_grafico').val();
    //             },
    //             // dataSrc: function (json) {
    //             //     console.log('Respuesta del servidor:', json);

    //             //     // Validar si la respuesta tiene error
    //             //     if (json.error) {
    //             //         console.error('Error en los datos recibidos:', json.message);
    //             //         Swal.fire({
    //             //             icon: 'error',
    //             //             title: 'Error al generar el gráfico',
    //             //             text: json.message || 'Error desconocido.'
    //             //         });
    //             //         return [];
    //             //     }

    //             //     // Verificar si graficoData está disponible y es válido
    //             //     if (json.graficoData && !json.graficoData.error && json.graficoData.labels && json.graficoData.numVentas && json.graficoData.montosTotales) {
    //             //         console.log('Datos del gráfico recibidos:', json.graficoData);
    //             //         generarGrafico(json.graficoData);
    //             //     } else {
    //             //         console.error('Datos de gráfico inválidos o incompletos:', json.graficoData);
    //             //         Swal.fire({
    //             //             icon: 'error',
    //             //             title: 'Error en los datos del gráfico',
    //             //             text: 'Los datos del gráfico no son válidos o están incompletos.'
    //             //         });
    //             //     }

    //             //     // Retornar los datos para la tabla
    //             //     return json.data || [];
    //             // },

    //             error: function(xhr, error, code) {
    //                 // Muestra detalles del error en la consola
    //                 console.error("Error de AJAX:", error);
    //                 console.error("Código de error:", code);
    //                 console.error("Respuesta del servidor:", xhr.responseText);

    //                 // Muestra un mensaje en la interfaz si lo deseas
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Error al cargar los datos',
    //                     text:  xhr.responseText || 'Hubo un problema al cargar los datos. Revisa la consola para más detalles.'
    //                 });
    //             }
    //         },
            
    //         columns: [
    //             {
    //                 data: 'id', // Campo de ID de venta
    //                 name: 'id',
    //                 visible: false
    //             },
    //             {
    //                 data: 'fecha',
    //                 name: 'fecha'
    //             },
    //             {
    //                 data: 'boleta',
    //                 name: 'boleta'
    //             },
    //             {
    //                 data: 'cliente',
    //                 name: 'cliente'
    //             },
    //             {
    //                 data: 'ruc_dni',
    //                 name: 'ruc_dni'
    //             },
    //             {
    //                 data: 'producto',
    //                 name: 'producto'
    //             },

    //             {
    //                 data: 'codigoproducto',
    //                 name: 'codigoproducto'
    //             },
    //             {
    //                 data: 'cantidad',
    //                 name: 'cantidad'
    //             },
    //             {
    //                 data: 'importe',
    //                 name: 'importe'
    //             },
    //             {
    //                 data: 'igv',
    //                 name: 'igv'
    //             },
    //             {
    //                 data: 'ganancia',
    //                 name: 'ganancia'
    //             }
                
                
    //         ]
    //     });

    //     //generarGrafico(response, agrupacion, tipoGrafico);

    //     // $('#filtrar').click(function () {

    //     // var fechaInicio = $('#fecha_inicio').val();
    //     // var fechaFin = $('#fecha_fin').val();
    //     // console.log("Fecha Inicio: ", fechaInicio);
    //     // console.log("Fecha Fin: ", fechaFin);
    //     // // Recargar la tabla
    //     // table.ajax.reload();

           
    //     //  });
        

    //     // Segundo AJAX para generar el gráfico
    //     // function obtenerDatosGrafico() {
    //     //     $.ajax({
    //     //         url: "{{ route('reporte.grafico') }}", // Ruta para obtener los datos del gráfico
    //     //         type: "GET",
    //     //         data: function(d) {
    //     //             d.fecha_inicio = $('#fecha_inicio').val();
    //     //             d.fecha_fin = $('#fecha_fin').val();
    //     //             d.producto_id = $('#producto_id').val();
    //     //             d.tipo_grafico = $('#tipo_grafico').val();
    //     //             d.agrupacion = $('#agrupacion').val();
    //     //         },
                
    //     //         success: function(response) {
    //     //             console.log('Datos recibidos:', response);  // Verifica los datos que se reciben
    //     //             if (response.error) {
    //     //                 console.error('Error al obtener los datos del gráfico:', response.message);
    //     //                 Swal.fire({
    //     //                     icon: 'error',
    //     //                     title: 'Error al generar el gráficod desde inicio de ajax',
    //     //                     text: response.message || 'Error desconocido.'
    //     //                 });
    //     //             } else {

                        
    //     //                 // Llama a la función para generar el gráfico con los datos recibidos
    //     //                 generarGrafico(response);
    //     //             }
    //     //         },
    //     //         error: function(xhr, error, code) {
    //     //             console.error('Error en la solicitud AJAX del gráfico:', error);
    //     //             Swal.fire({
    //     //                 icon: 'error',
    //     //                 title: 'Error al obtener los datos del gráfico',
    //     //                 text: 'Hubo un problema al obtener los datos para el gráfico. Revisa la consola para más detalles.'
    //     //             });
    //     //         }
    //     //     });
    //     // }

        
    //     // Obtener los valores de los inputs al cargar la página
    //     const agrupacion = $('#agrupacion').val();
    //     const productoId = $('#producto_id').val();
    //     const tipoGrafico = $('#tipo_grafico').val();
    //     const fechaInicio = $('#fecha_inicio').val();
    //     const fechaFin = $('#fecha_fin').val();

    //     // Validar que los valores no estén vacíos antes de hacer la solicitud
    //     if (!agrupacion || !tipoGrafico || !fechaInicio || !fechaFin) {
    //         Swal.fire({
    //             icon: 'warning',
    //             title: 'Datos incompletos',
    //             text: 'Por favor, configura todos los campos correctamente antes de cargar la página.'
    //         });
    //         return;
    //     }

    //     // Llamar a la función para obtener los datos automáticamente
    //     obtenerDatosGrafico(agrupacion, productoId, tipoGrafico, fechaInicio, fechaFin);

    //     // Función para realizar la solicitud AJAX con método GET
    //     function obtenerDatosGrafico(agrupacion, productoId, tipoGrafico, fechaInicio, fechaFin) {
    //         const datos = {
    //             agrupacion: agrupacion,
    //             producto_id: productoId,
    //             tipo_grafico: tipoGrafico,
    //             fecha_inicio: fechaInicio,
    //             fecha_fin: fechaFin
    //         };

    //         console.log('Datos enviados:', datos); // Verifica los datos enviados

    //         $.ajax({
    //             url: "{{ route('reporte.grafico') }}", // Cambia esta ruta según tu backend
    //             method: 'GET', // Cambia a GET
    //             data: datos, // Los datos se enviarán como parámetros en la URL
    //             success: function(response) {
    //                 console.log('Respuesta del servidor:', response);

    //                 if (response.error) {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'Error en el servidor',
    //                         text: response.message || 'Error desconocido.'
    //                     });
    //                     return;
    //                 }

    //                 // Llama a la función para procesar los datos
    //                 procesarDatosGrafico(response);
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Error al obtener los datos',
    //                     text: 'No se pudo realizar la solicitud. Verifica la conexión o los datos enviados.'
    //                 });
    //             }
    //         });
    //     }

    //     // Función para procesar los datos y generar el gráfico
    //     function procesarDatosGrafico(datos) {
    //         console.log('Procesando datos para el gráfico:', datos);

    //         if (!datos.labels || datos.labels.length === 0) {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Datos inválidos',
    //                 text: 'No se encontraron datos válidos para el gráficoddd.'
    //             });
    //             return;
    //         }

    //         generarGrafico(datos);
    //     }

    //     // Función para generar el gráfico
    //     function generarGrafico(data) {
    //         console.log('Generando gráfico con los datos:', data);

    //         let ctx = $('#ventasChart')[0].getContext('2d');
            
    //         if (window.chartInstance) {
    //             window.chartInstance.destroy();
    //         }

    //         let chartData = {
    //             labels: data.labels,
    //             datasets: [
    //                 {
    //                     label: 'Número de Ventas',
    //                     data: data.numVentas,
    //                     backgroundColor: 'rgba(104, 223, 214, 0.95)',
    //                 },
    //                 {
    //                     label: 'Monto Total (S/)',
    //                     data: data.montosTotales,
    //                     backgroundColor: 'rgba(213, 248, 52, 0.95)',
    //                 }
    //             ]
    //         };

    //         window.chartInstance = new Chart(ctx, {
    //             type: data.tipoGrafico || 'bar',
    //             data: chartData,
    //             options: {
    //                 responsive: true,
    //                 scales: {
    //                     y: { beginAtZero: true }
    //                 }
    //             }
    //         });
    //     }



    //     $('#filtrar').click(function () {
    //         var fechaInicio = $('#fecha_inicio').val();
    //         var fechaFin = $('#fecha_fin').val();
    //         var productoId = $('#producto_id').val();
    //         var agrupacion = $('#agrupacion').val();
    //         var tipoGrafico = $('#tipo_grafico').val();

    //         table.ajax.reload();

    //         $.ajax({
    //             url: "{{ route('reporte.producto') }}",
    //             type: "GET",
    //             data: {
    //                 fecha_inicio: fechaInicio,
    //                 fecha_fin: fechaFin,
    //                 producto_id: productoId,
    //                 agrupacion: agrupacion,
    //                 tipo_grafico: tipoGrafico,
    //             },

    //             dataSrc: function (json) {
    //                 console.log('Respuesta AJAX:', json); // Verifica la estructura de la respuesta
    //                 return json.graficoData;
    //                // return json.data.original.data;  // Asegúrate de que aquí estés extrayendo los datos correctamente
    //             },
    //             success: function(response) {
    //                 // // Actualizar tabla de ventas
    //                 // table.clear().rows.add(response.data).draw();
                    
    //                 // Generar gráfico con los datos recibidos
    //                 generarGrafico(response);
    //             }
    //         });
    //     });

    //     // function generarGrafico(data) {
            

    //     //     // Verificar si data tiene los campos esperados
    //     //     if (!data.labels) {
    //     //         console.error('Datos inválidos para generar el gráfico desde funcion generargrafico:', data);
    //     //         Swal.fire({
    //     //             icon: 'error',
    //     //             title: 'Error en los datos',
    //     //             text: data?.message || 'No se pudieron procesar los datos para el gráfico desde generargrafico.'
    //     //         });
    //     //         return;
    //     //     }
    //     //     let ctx = $('#ventasChart')[0].getContext('2d');
            
    //     //     // Verificar si ya existe un gráfico y destruirlo
    //     //     if (window.chartInstance) {
    //     //         window.chartInstance.destroy();
    //     //     }
    //     //     if (!data ) {
    //     //         Swal.fire({
    //     //             icon: 'error',
    //     //             title: 'Error al generar el gráfico',
    //     //             text: data.message || 'No se encontraron datos válidos para el gráfico. vacío el'
    //     //         });
    //     //         return;
    //     //     }
            

    //     //     labels = data.labels;
    //     //     numVentas= data.numVentas;
    //     //     montosTotales = data.montosTotales;
    //     //     tipoGrafico = data.tipoGrafico;

    //     //     // Configuración común para los tres tipos de gráficos
    //     //     let chartData = {
    //     //         labels: labels,
    //     //         datasets: [
    //     //             {
    //     //                 label: 'Número de Ventas',
    //     //                 data: numVentas,
    //     //                 backgroundColor: 'rgba(104, 223, 214, 0.95)', // Color de las barras
    //     //                 borderColor: 'rgba(104, 223, 214, 0.95)',
    //     //                 borderWidth: 1
    //     //             },
    //     //             {
    //     //                 label: 'Monto Total (S/)',
    //     //                 data: montosTotales,
    //     //                 backgroundColor: 'rgba(213, 248, 52, 0.95)', // Color del monto total
    //     //                 borderColor: 'rgba(213, 248, 52, 0.95)',
    //     //                 borderWidth: 1
    //     //             }
    //     //         ]
    //     //     };

    //     //     let options = {
    //     //         responsive: true,
    //     //         scales: {
    //     //             x: { beginAtZero: true },
    //     //             y: { beginAtZero: true }
    //     //         }
    //     //     };

    //     //     // Elegir el tipo de gráfico
    //     //     if (tipoGrafico === 'bar') {
    //     //         window.chartInstance = new Chart(ctx, {
    //     //             type: 'bar',
    //     //             data: chartData,
    //     //             options: options
    //     //         });
    //     //     } else if (tipoGrafico === 'line') {
    //     //         window.chartInstance = new Chart(ctx, {
    //     //             type: 'line',
    //     //             data: chartData,
    //     //             options: options
    //     //         });
    //     //     } else if (tipoGrafico === 'pie') {
    //     //         let pieData = {
    //     //             labels: labels,
    //     //             datasets: [{
    //     //                 data: montosTotales,
    //     //                 backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de','#10c0ef'],
    //     //             }]
    //     //         };
    //     //         window.chartInstance = new Chart(ctx, {
    //     //             type: 'pie',
    //     //             data: pieData,
    //     //             options: {
    //     //                 responsive: true,
    //     //                 maintainAspectRatio: false
    //     //             }
    //     //         });
    //     //     }
    //     // }

    //     //obtenerDatosGrafico(); // Llamada para obtener los datos del gráfico

    //     // Función para habilitar/deshabilitar opciones según el rango de fechas
    //     function actualizarAgrupacion() {
    //         var fechaInicio = $('#fecha_inicio').val();
    //         var fechaFin = $('#fecha_fin').val();

    //         if (fechaInicio && fechaFin) {
    //             // Ajustar las fechas para incluir horas
    //             var inicio = moment(fechaInicio).startOf('day'); // 00:00:00
    //             var fin = moment(fechaFin).endOf('day'); // 23:59:59
    //             var dias = fin.diff(inicio, 'days'); // Calculamos la diferencia en días

    //             // Deshabilitar todas las opciones inicialmente
    //             $('#agrupacion option').prop('disabled', true);

    //             if (dias > 90) { // Más de 3 meses
    //                 $('#agrupacion option[value="mes"]').prop('disabled', false);
    //                 $('#agrupacion option[value="anual"]').prop('disabled', false);
    //             } else if (dias > 7) { // Entre 7 días y 3 meses
    //                 $('#agrupacion option[value="dia"]').prop('disabled', true);
    //                 $('#agrupacion option[value="semana"]').prop('disabled', false);
    //                 $('#agrupacion option[value="mes"]').prop('disabled', false);
    //                 $('#agrupacion option[value="anual"]').prop('disabled', false);
    //             } else { // Hasta 7 días
    //                 $('#agrupacion option').prop('disabled', false); // Todas habilitadas
    //             }

    //             // Seleccionar automáticamente la primera opción habilitada
    //             //$('#agrupacion option:enabled').first().prop('selected', true);
    //         }
    //     }

    //     // Escuchamos cambios en las fechas para actualizar la agrupación
    //     $('#fecha_inicio, #fecha_fin').change(function() {
    //         actualizarAgrupacion();
    //     });

    //     // Configuración inicial de fechas: 1 mes y 13 días por defecto
    //     // var fechaActual = moment(); // Fecha actual
    //     // $('#fecha_inicio').val(fechaActual.clone().subtract(1, 'month').subtract(13, 'days').format('YYYY-MM-DD'));
    //     // $('#fecha_fin').val(fechaActual.format('YYYY-MM-DD'));

    //     // Inicializamos la agrupación al cargar la página
    //     actualizarAgrupacion();

    //     //grafico barras

        

    // });

    $(document).ready(function () {

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

        let tablaVentas;

        // Inicializar DataTable vacío
        function inicializarDataTable(data) {
            if (tablaVentas) {
                tablaVentas.destroy();
            }
            tablaVentas = $('#table-ventasxproductos').DataTable({

                responsive: true, // Habilitar la opción responsive
                autoWidth: false,
                searchDelay : 2000,
                processing: true,
                scrollX: true, // Activa el desplazamiento horizontal
                scroller: true, // Usa el plugin Scroller para manejar el desplazamiento en tablas grandes
                "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página", // Asegúrate de usar _MENU_
                "zeroRecords": "Nada encontrado - disculpa",
                "info": "Mostrando la página _PAGE_ de _PAGES_", // Usa _PAGE_ y _PAGES_ para interpolar correctamente
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
                data: data,
                columns: [
                    {
                        data: 'id', // Campo de ID de venta
                        name: 'id',
                        visible: false
                    },
                    {
                        data: 'fecha',
                        name: 'fecha'
                    },
                    {
                        data: 'boleta',
                        name: 'boleta'
                    },
                    {
                        data: 'cliente',
                        name: 'cliente'
                    },
                    {
                        data: 'ruc_dni',
                        name: 'ruc_dni'
                    },
                    {
                        data: 'producto',
                        name: 'producto'
                    },

                    {
                        data: 'codigoproducto',
                        name: 'codigoproducto'
                    },
                    {
                        data: 'cantidad',
                        name: 'cantidad'
                    },
                    {
                        data: 'importe',
                        name: 'importe'
                    },
                    {
                        data: 'igv',
                        name: 'igv'
                    },
                    {
                        data: 'ganancia',
                        name: 'ganancia'
                    }
                    
                    
                ]
            });
        }

        // Actualizar gráfico
        // function actualizarGrafico(labels, data) {
        //     const ctx = document.getElementById('graficoVentas').getContext('2d');
        //     if (window.grafico) {
        //         window.grafico.destroy();
        //     }
        //     window.grafico = new Chart(ctx, {
        //         type: 'line',
        //         data: {
        //             labels: labels,
        //             datasets: [{
        //                 label: 'Ventas por fecha',
        //                 data: data,
        //                 borderColor: 'rgba(75, 192, 192, 1)',
        //                 borderWidth: 2,
        //                 fill: false,
        //             }]
        //         },
        //         options: {
        //             responsive: true,
        //             plugins: {
        //                 legend: {
        //                     display: true,
        //                 },
        //             },
        //             scales: {
        //                 x: {
        //                     title: {
        //                         display: true,
        //                         text: 'Fecha'
        //                     }
        //                 },
        //                 y: {
        //                     title: {
        //                         display: true,
        //                         text: 'Monto Total (S/.)'
        //                     }
        //                 }
        //             }
        //         }
        //     });
        // }
        function actualizarGrafico(labels, numVentas, montosTotales,agrupacion,tipo_grafico) {
            const chartData = {
                labels: labels, // Asignar las etiquetas
                datasets: [
                    {
                        label: 'Ventas', // Título del gráfico
                        data: numVentas, // Datos de las ventas
                        borderColor: 'rgba(255, 99, 71, 1)', // Color de la línea
                        backgroundColor: 'rgba(255, 99, 71, 1)', // Color de fondo
                        borderWidth: 1
                    },
                    {
                        label: 'Montos Totales', // Título del gráfico para montos
                        data: montosTotales, // Datos de los montos
                        borderColor: 'rgba(42, 15, 248, 0.8)', // Otro color para los montos
                        backgroundColor: 'rgba(42, 15, 248, 0.8)', // Color de fondo para los montos
                        borderWidth: 1
                    }
                ]
            };

            // Determinar el subtítulo según la agrupación
            let subtitle = '';
            if (agrupacion === 'mes') {
                subtitle = 'Agrupación por Meses';
            } else if (agrupacion === 'semana') {
                subtitle = 'Agrupación por Semanas';
            } else if (agrupacion === 'dia') {
                subtitle = 'Agrupación por Días';
            } else if (agrupacion === 'anio') {
                subtitle = 'Agrupación por Años';
            }

            // Determinar el tipo de gráfico: 'bar', 'line' o 'pie'
            const tipo = tipo_grafico || 'bar';  // Default es 'line' si no se pasa el tipo

            // Si ya existe un gráfico, lo eliminamos y creamos uno nuevo
            if (window.myChart) {
                window.myChart.destroy();  // Destruir el gráfico existente
            }

            // Crear el gráfico nuevo
            const ctx = document.getElementById('graficoVentas').getContext('2d');
            window.myChart = new Chart(ctx, {
                type: tipo,        // Tipo de gráfico (puede ser 'bar', 'line', 'pie', etc.)
                data: chartData,   // Datos a graficar
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        subtitle: {
                            display: true,
                            text: subtitle  // Mostrar el subtítulo según la agrupación
                        }
                    },
                    scales: tipo === 'pie' ? {} : { // Para gráficos tipo 'pie' no se necesitan ejes Y
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }


        // Manejar el botón de Filtrar
        $('#btnFiltrar').click(function () {
            const filtros = $('#formFiltros').serialize(); // Serializa los filtros
            $.ajax({
                url: '/reporteProducto',
                type: 'GET',
                data: filtros,
                success: function (response) {
                    inicializarDataTable(response.tabla.original.data);

                    const graficoData = Object.values(response.grafico);  // Obtener los valores


                    const labels = graficoData[0];         // labels es el primer valor
                    const numVentas = graficoData[1];      // numVentas es el segundo valor
                    const montosTotales = graficoData[2];  // montosTotales es el tercer valor
                    const agrupacion = graficoData[3];
                    const tipo_grafico = graficoData[4];

                    actualizarGrafico(labels, numVentas, montosTotales,agrupacion,tipo_grafico);
                    //actualizarGrafico(Object.keys(response.grafico), Object.values(response.grafico));

                    // Object.keys() devuelve las claves del objeto, en este caso ["labels", "numVentas", "montosTotales"].
                    // Object.values() devuelve los valores correspondientes a esas claves, en este caso [[2024-01-01, 2024-01-02, 2024-01-03], [5, 3, 7], [500, 300, 700]].
                },
                error: function (error) {
                    console.error('Error al cargar los datos:', error);
                }
            });
        });

        // Cargar datos iniciales
        $('#btnFiltrar').click(); // Simula un clic para cargar los datos iniciales

        function actualizarAgrupacion() {
            var fechaInicio = $('#fecha_inicio').val();
            var fechaFin = $('#fecha_fin').val();

            if (fechaInicio && fechaFin) {
                var inicio = moment(fechaInicio).startOf('day');
                var fin = moment(fechaFin).endOf('day');
                var dias = fin.diff(inicio, 'days');

                $('#agrupacion option').prop('disabled', true);

                if (dias > 90) {
                    $('#agrupacion option[value="mes"]').prop('disabled', false);
                    $('#agrupacion option[value="anual"]').prop('disabled', false);
                } else if (dias > 7) {
                    $('#agrupacion option[value="dia"]').prop('disabled', true);
                    $('#agrupacion option[value="semana"]').prop('disabled', false);
                    $('#agrupacion option[value="mes"]').prop('disabled', false);
                    $('#agrupacion option[value="anual"]').prop('disabled', false);
                } else {
                    $('#agrupacion option').prop('disabled', false);
                }
            }
        }

        $('#fecha_inicio, #fecha_fin').change(function() {
            actualizarAgrupacion();
            // Obtén los valores de los inputs
            const input1Value = document.getElementById("fecha_inicio").value;
            // Asigna el valor del primer input al segundo
            document.getElementById("fecha_inicio_dinamico").value = input1Value;

            const input2Value = document.getElementById("fecha_fin").value;
            // Asigna el valor del primer input al segundo
            document.getElementById("fecha_fin_dinamico").value = input2Value;

            
        });

        actualizarAgrupacion();

        // Script para exportar el gráfico a PDF
        document.getElementById('exportPdfForm').addEventListener('submit', function (event) {
            // Previene el envío del formulario hasta obtener el gráfico
            event.preventDefault();
            //para guardar el idproducto
            const input3Value = document.getElementById("producto_id").value;
            // Asigna el valor del primer input al segundo
            document.getElementById("producto_id_dinamico").value = input3Value;

            // Selecciona el elemento del gráfico
            var chartCanvas = document.getElementById('graficoVentas');

            // Convierte el gráfico a una imagen Base64
            var chartBase64 = chartCanvas.toDataURL('image/png');

            // Establece el valor del campo oculto
            document.getElementById('chartInput').value = chartBase64;

            // Envía el formulario
            this.submit();
        });

    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

@endsection