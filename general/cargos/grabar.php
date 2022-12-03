
<?php
require'../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_car = $_POST['id_car'];
$car_descrip = $_POST['car_descrip'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion,"select sp_cargos($id_car,'$car_descrip','$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
