<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_or = $_POST['id_or'];
if($id_or == '-1'){ //AllPRESIONAR CANCELAR
    //sdffffffffffffffffffffh
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_or == '0'){ //AL PRESIONAR AGREGAR
?>
<div class="card-success">
    <div class="card-header">
        Ordenes
    </div>
    <div class="card-body">
    <div class="col-sm-4"> 
        <div class="form-group">

            <label>Sucursal</label>
            <input class="form-control" type="text" disabled="" value="<?= $_SESSION['suc_nombre'];?>">
        </div>
        </div>
        <div class="col-sm-4">
        <div class="form-group">

            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= date('Y-m-d'); ?>" id="or_fecha">
        </div>
        </div>
        <div class="col-sm-4">

<label>Fabricante</label>
<select class="select2" id="agregar_id_pro">
    <?php foreach($proveedor as $pr){ ?>
    <option value="<?=  $pr['id_pro'];?>"><?= $pr['pro_descrip']."";?></option>
    <?php } ?>
</select>
</div>
        <div class="form-group form-float">
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
    if($id_or == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_orden WHERE id_or = (select max(id_or) from orden);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_orden WHERE id_or = $id_or;"));
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
        <input type="hidden" id="id_or" value="<?= $cab[0]['id_or'];?>">
        <input type="hidden" id="eliminar_id_item" value="0">
        <div class="form-group">
            <label>Sucursal</label>
            <input class="form-control" type="text" disabled="" value="<?= $cab[0]['suc_nombre'];?>">
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= $cab[0]['or_fecha']; ?>" <?= $deshabilitado;?> id="or_fecha">
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