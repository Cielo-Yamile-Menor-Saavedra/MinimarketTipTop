<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Token CSRF -->
  <title>@yield('titulo')</title><!--parte cambiante con yield-->
  <script src="https://kit.fontawesome.com/072340084d.js" crossorigin="anonymous"></script>

  <!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- Incluye Scroller desde la CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.7/css/scroller.dataTables.min.css">

<!-- Incluye css de datatable -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">


  <!-- Theme style -->
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/css/darkmode.css">
  <link rel="stylesheet" href="/css/imagenfondo.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  {{--libreria sweetalert2--}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{--JQUERY--}}
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

  {{--para datatables--}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.bootstrap5.css"> --}}

  <!-- CSS de Scroller -->
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.7/css/scroller.dataTables.min.css">

<!-- JS de Scroller -->
<script src="https://cdn.datatables.net/scroller/2.0.7/js/dataTables.scroller.min.js"></script>






</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class=" wrapper  ">
  <!-- Navbar -->
  <nav id="navprincipal" class="main-header navbar navbar-expand navbar-light navbar-white">
    <!-- Left navbar links -->


    <ul class="navbar-nav ml-auto">

      <li>
        <div class="row">
          <div class="col">
            <a href="{{ url('/apk/app-release.apk') }}" class="btn btn-primary">
              📥App
            </a>
          </div>
          <div class="col">
            <form action="{{route('logout')}}" method="post" class="col-2">
              @csrf<!-- crea un token de seguridad-->
              <button class="btn btn-warning" type="submit">
                <i class="fa fa-power-off"></i> Salir</button>
    
            </form>
          </div>
        </div>
        
        

      </li>
    </ul>

    {{--<form action="route('logout')" method="post" class="col-1">
      @csrf<!-- crea un token de seguridad-->
      <button class="btn btn-info btn-block" type="submit">
        <i class="fa fa-power-off"></i> Salir</button>

    </form>--}}

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar  sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <a href="{{route('home')}}" style="text-decoration:none;" class="brand-link">
      Minimarket <img style="margin-left: 0" src="/images/login/logo.png" alt="logo" width="" height="30" />
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex ">
        <div class="image">
          {{-- <i class="fa fa-user-circle" aria-hidden="true"></i> --}}
           {{-- <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
           <img id="image-inicial" name="image-inicial"
            src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('storage/avatares/placeholder.png') }}"
            class="img-circle elevation-2" alt="User Image"/>
        </div>
        <div class="info usuario">
          <a href=""  class="d-block" style="text-decoration:none;">{{auth()->user()->name}}</a>
          <a class="d-block" style="text-decoration:none;"></a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item {{ request()->is('usuario*') || request()->is('permiso*') || request()->is('rol*') ? 'menu-open' : '' }}" >
            <a href="{{--route('prestamo.index')--}}" class="nav-link {{ request()->is('usuario*') || request()->is('permisos*') || request()->is('roles*') ? 'active' : '' }}" >
              <i class="fas fa-shield-alt"></i>
              <p>
                SEGURIDAD
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('permiso.index')
              <li class="nav-item">
                <a href="{{route('permiso.index')}}" class="nav-link {{ request()->is('permiso*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permisos</p>
                </a>
              @endcan
              </li>
              @can('role.index')
              <li class="nav-item">
                <a href="{{route('role.index')}}" class="nav-link {{ request()->is('rol*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              @endcan
              </li>
              <li class="nav-item">
                <a href="{{route('usuario.index')}}" class="nav-link {{request()->is('usuario*') ? 'active' : ''}}" >
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usuario</p>
                </a>

              </li>
            </ul>
          </li>

<li class="nav-item {{ request()->is('perfil*') || request()->is('contraseña*') ? 'menu-open' : '' }}"  >
    <a href="{{--route('perfil.index')--}}" class="nav-link {{ request()->is('perfil*') || request()->is('contraseña*') ? 'active' : '' }}">
      <i class="fa fa-user-circle" aria-hidden="true"></i>
      {{-- <i class="nav-icon fas fa-chart-pie"></i> --}}
      <p>
        Mi Perfil
         <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="{{route('perfil.index')}}" class="nav-link {{ request()->is('perfil*') ? 'active' : '' }}">
          <i class="far fa-circle nav-icon"></i>
          <p>Datos personales</p>
        </a>
      </li>
    </ul>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="{{route('perfil.contraseña')}}" class="nav-link {{ request()->is('contraseña*') ? 'active' : '' }}">
          <i class="far fa-circle nav-icon"></i>
          <p>Configuración</p>
        </a>
      </li>
    </ul>
  </li>

  <li class="nav-item">
    <a href="#" class="nav-link">
        <i class="fa-solid fa-layer-group"></i>
        <p>
            Categorias
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('categoria.index') }}" class="nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de categorias</p>
            </a>

        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="fa-solid fa-cube"></i>
        <p>
            Almacén
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('producto.index') }}" class="nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de productos</p>
            </a>

        </li>
    </ul>

    <ul class="nav nav-treeview">

        <li class="nav-item {{ request()->is('producto*')  ? 'menu-open' : '' }}">
            <a href="{{ route('producto.create') }}" class="nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Creación de productos</p>
            </a>

        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link {{ request()->is('*')  ? 'menu-open' : '' }}">
        <i class="fa-solid fa-clipboard-list"></i>
        <p>
            Orden de compra
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('orden.index')}}" class="nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('orden.create')}}" class="nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Crear orden</p>
            </a>
        </li>
    </ul>
</li>


<li class="nav-item {{ request()->is('compra*')  ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('compra*') ? 'active' : '' }}">
        <i class="fa-solid fa-cart-shopping"></i>
        <p>
            Compras
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('compra.index') }}" class="nav-link {{ request()->is('compras') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de compras</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('compra.create') }}" class="nav-link {{ request()->is('compras/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Creación de compras</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item {{ request()->is('venta*')  ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('venta*') ? 'active' : '' }}" >
        <i class="fa-solid fa-store"></i>
        <p>
            Ventas
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('venta.create') }}" class="nav-link {{ request()->is('venta/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Realizar venta</p>
            </a>
        </li>
    </ul>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('venta.index') }}" class="nav-link {{ request()->is('venta') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de ventas</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="fas fa-people-arrows"></i>
        <p>
            Clientes
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('cliente.index') }}" class="nav-link ">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado de clientes</p>
            </a>

        </li>
    </ul>


<li class="nav-item {{ request()->is('proveedor*')  ? 'menu-open' : '' }}">
  <a href="{{route('proveedor.index')}}" class="nav-link {{ request()->is('proveedor*') ? 'active' : '' }}">
    <i class="fas fa-user-tie"></i>
      <p>
          Proveedores
      </p>
  </a>
</li>

<li class="nav-item {{ request()->is('reporte*') || request()->is('gestion*') ? 'menu-open' : '' }}" >
  <a href="{{--route('prestamo.index')--}}" class="nav-link {{ request()->is('reporte*') ? 'active' : '' }}" >
    <i class="fas fa-shield-alt"></i>
    <p>
      Reportes
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    @can('permiso.index')
    <li class="nav-item">
      <a href="{{route('reporte.create')}}" class="nav-link {{ request()->is('reporte/create') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Panel</p>
      </a>
    @endcan
    </li>
    @can('role.index')
    <li class="nav-item">
      <a href="{{route('reporte.index')}}" class="nav-link {{ request()->is('reporte') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Gráficos</p>
      </a>
    @endcan
    </li>
  </ul>
</li>

<li class="nav-item">
    <a href="https://prediccion-ventas.streamlit.app" target="_blank" class="nav-link">
        <i class="fa-solid fa-chart-simple"></i>
        <p>
            Predicción de ventas
        </p>
    </a>
  </li>




        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <section class="content">
        @yield('contenido')<!--PARA cambiar este contenido en otra vista-->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="display: flex; justify-content: center; align-items: center; text-align: center;">
    © Todos los derechos reservados Minimarket Tip Top S.A.C. Av. Leoncio Prado N°14, Pacasmayo, Perú.
</footer>


  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/adminlte/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE App -->
<script src="/adminlte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/adminlte/dist/js/demo.js"></script>
<script src="/js/dark.js"></script>
<script src="https://cdn.datatables.net/scroller/2.0.7/js/dataTables.scroller.min.js"></script>
@yield('script')
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

{{--para datables--}}
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/responsive.bootstrap5.js"></script>




<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

<!-- JS para exportar a PDF, Excel, etc. -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


</html>
