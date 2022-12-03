<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_ped = $_POST['id_ped'];
if($id_ped == '-1'){ //AL PRESIONAR CANCELAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_ped == '0'){ //AL PRESIONAR AGREGAR
?>
<div class="card-success">
    <div class="card-header">
        Datos del Pedido
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Sucursal</label>
            <input class="form-control" type="text" disabled="" value="<?= $_SESSION['suc_nombre'];?>">
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= date('Y-m-d'); ?>" id="ped_fecha">
        </div>
        <div class="btn-group">
            <button class="btn btn-danger" onclick="cancelar();">
                <i class="fa fa-ban"></i> Cancelar
            </button>
            <button class="btn btn-success" onclick="agregar_grabar();">
                <i class="fa fa-save"></i> Grabar
            </button>
        </div>
    </div>
</div>
<?php
}else{
    if($id_ped == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_compra_pedidos WHERE id_ped = (select max(id_ped) from compra_pedidos);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_compra_pedidos WHERE id_ped = $id_ped;"));
    }
    if($cab[0]['estado'] == 'PENDIENTE'){ //PARA HABILITAR O DESHABILITAR CAMPOS
        $deshabilitado = "";
    }else{
        $deshabilitado = "disabled";
    } ?>
<div class="card-success">
    <div class="card-header">
        Datos del Pedido
    </div>
    <div class="card-body">
        <input type="hidden" id="id_ped" value="<?= $cab[0]['id_ped'];?>">
        <input type="hidden" id="eliminar_id_item" value="0">
        <div class="form-group">
            <label>Sucursal</label>
            <input class="form-control" type="text" disabled="" value="<?= $cab[0]['suc_nombre'];?>">
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= $cab[0]['ped_fecha']; ?>" <?= $deshabilitado;?> id="ped_fecha">
        </div>
        <div class="btn-group">
            <button class="btn btn-danger" onclick="cancelar();">
                <i class="fa fa-ban"></i> Cancelar
            </button>
            <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
            <button class="btn btn-success" onclick="confirmar();"><i class="fa fa-check-circle"></i> Confirmar</button>
            <button class="btn btn-warning text-white" onclick="modificar();"><i class="fa fa-edit"></i> Modificar</button>
            <button class="btn btn-danger" onclick="anular();"><i class="fa fa-minus-circle"></i> Anular</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php
} ?>