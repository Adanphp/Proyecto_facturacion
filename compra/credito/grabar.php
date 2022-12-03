<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_cre = $_POST['id_cre'];
$id_suc = $_SESSION['id_suc'];
$fecha = $_POST['fecha'];
$com = $_POST['com'];
$id_tim = $_POST['id_tim'];
$id_doc = $_POST['id_doc'];
$id_pro = $_POST['id_pro'];
$id_tip = $_POST['id_tip'];
$intervalo_cuota = $_POST['intervalo_cuota'];
$id_item = $_POST['id_item'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$iva = $_POST['iva'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
try{ 
$grabar = pg_query($conexion, "SELECT sp_credito($id_cre, $id_suc, '$fecha',$com, $id_tim, $id_pro, $id_doc, $id_tip, '$intervalo_cuota', $id_item, $cantidad, $precio, $iva, '$usuario', $operacion);");
 } catch(\throwable $th) {
    var_dump($th);
 }

if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
