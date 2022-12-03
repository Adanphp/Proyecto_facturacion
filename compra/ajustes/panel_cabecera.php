<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_ajus = $_POST['id_ajus'];
if($id_ajus == '-1'){ //AL PRESIONAR CANCELAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_ajus == '0'){ //AL PRESIONAR AGREGAR
?>

<?php // DEFINIMOS EL FORMULARIO CORRESPONDIENTE PARA LA CABECERA ?>
<div class="card-success"> 
    <div class="card-header">
        Agregar Cabecera
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Sucursal</label>
            <input class="form-control" type="text" value="<?= $_SESSION['suc_nombre'];?>">
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= date('Y-m-d'); ?>" id="fecha">
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
    if($id_ajus == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_ajustes WHERE id_ajus = (select max(id_ajus) from ajustes);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_ajustes WHERE id_ajus = $id_ajus;"));
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
        <input type="hidden" id="id_ajus" value="<?= $cab[0]['id_ajus'];?>">
        <input type="hidden" id="eliminar_id_item" value="0">
        <div class="form-group">
            <label>Sucursal</label>
            <input class="form-control" type="text" disabled="" value="<?= $cab[0]['suc_nombre'];?>">
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= $cab[0]['fecha']; ?>" <?= $deshabilitado;?> id="fecha">
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