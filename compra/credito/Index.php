<?php
  include "../../conexion.php";
  include "../../session.php";
  $_SESSION['id_pag'] = '7';
  include "../../permiso.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Informatic Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/tercero/iconos/informatic.jpg" type="jpg">
    <link rel="stylesheet" href="/tercero/estilo/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/tercero/estilo/descarga/font-google.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/tercero/estilo/descarga/tabla1.min.css">
    <link rel="stylesheet" href="/tercero/estilo/descarga/tabla2.min.css">
  </head>
  <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">
      <?php include ("../../cabecera.php"); ?>
      <?php include ("../../menu.php"); ?>
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-dark">Nota De Credito Y Debito</h1>
                <input type="hidden" id="operacion" value="0">
                <input type="hidden" id="btn-panel-modificar" data-target="#panel-modificar" data-toggle="modal">
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">Compras</li>
                  <li class="breadcrumb-item active">Orden de Compra</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <section class="content">
            <div class="card">
                <div class="card-header p-0">
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active" href="#panel-credito" id="btn-panel-credito" data-toggle="tab">Credito</a></li>
                        <li class="nav-item"><a class="nav-link" href="#panel-cabecera" id="btn-panel-cabecera" data-toggle="tab">Cabecera</a></li>
                        <li class="nav-item"><a class="nav-link" href="#panel-detalles" id="btn-panel-detalles" data-toggle="tab">Detalles</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="panel-credito">
                            <label class="text-danger">
                                <i class="fa fa-exclamation-circle"></i> Lista de Ordenes 
                            </label>
                        </div>
                        <div class="tab-pane" id="panel-cabecera">
                            <label class="text-danger">
                                <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
                            </label>
                        </div>
                        <div class="tab-pane" id="panel-detalles">
                            <label class="text-danger">
                                <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="panel-modificar">
                
            </div>
        </section>
      </div>
      <footer class="main-footer">
        <strong>Copyright &copy; 2021 <a href="#">Informatic</a></strong>
      </footer>
    </div>
    <script src="/tercero/estilo/plugins/jquery/jquery.min.js"></script>
    <script src="/tercero/estilo/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script> $.widget.bridge('uibutton', $.ui.button) </script>
    <script src="/tercero/estilo/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/tercero/estilo/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="/tercero/estilo/dist/js/adminlte.js"></script>
    <script src="/tercero/iconos/fontawesome.js"></script>
    <script src="/tercero/estilo/plugins/fastclick/fastclick.js"></script>
    <script src="/tercero/estilo/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/tercero/estilo/plugins/toastr/toastr.min.js"></script>
    <script src="/tercero/estilo/plugins/select2/js/select2.full.min.js"></script>
    <script src="/tercero/estilo/descarga/tabla1.min.js"></script>
    <script src="/tercero/estilo/descarga/tabla2.min.js"></script>
    <script src="/tercero/estilo/descarga/tabla3.min.js"></script>
    <script src="/tercero/estilo/descarga/tabla4.min.js"></script>
    <script src="funciones.js"></script>
    <?php include "../../mensaje.php";?>
  </body>
</html>