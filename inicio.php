<?php
	include "conexion.php";
	include "session.php";
  $_SESSION['id_pag'] = '0';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Informatic Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/tercero/iconos/camaleon.jpg" type="png">
    <link rel="stylesheet" href="/tercero/estilo/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/tercero/estilo/descarga/font-google.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="/tercero/estilo/plugins/toastr/toastr.min.css">
  </head>
  <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">
      <?php require "cabecera.php"; ?>
      <?php require "menu.php"; ?>
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-dark">Informatic Store</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">Inicio</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <section class="content">
          <div class="container-fluid">
              <?= date('Y-m-d H:m:s');?>
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
    <?php include "mensaje.php"; ?>
  </body>
</html>