<?php
$id_fac = $_POST['id_fac'];
$id_item = $_POST['id_item'];
include '../../conexion.php';
include '../../session.php';
$datos = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_factura_detalles WHERE id_fac = $id_fac AND id_item = $id_item;"));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card card-warning">
            <div class="card-header text-center text-white">
                Modificar Cantidad
            </div>
            <input type ="hidden" id="modificar_id_item" value="<?php echo $datos[0]['id_item']; ?>">
            <input type ="hidden" id="modificar_id_pro" value="<?php echo $datos[0]['id_pro']; ?>">
            <input type ="hidden" id="modificar_precio" value="<?php echo $datos[0]['precio']; ?>">
            <input type ="hidden" id="modificar_id_or" value="<?php echo $id_or; ?>">
            <div class="card-body">
                <input type="hidden" id="modificar_id_item" value="<?php echo $datos[0]['id_item']; ?>">
                <div class="form-group">
                    <label>Producto</label>
                    <input type="text" disabled="" value="<?php echo $datos[0]['item_descrip']; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" class="form-control" value="<?php echo $datos[0]['cantidad']; ?>" id="modificar_cantidad">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancelar</button>
                <button class="btn btn-warning text-white" onclick="modificar_detalle_grabar();"><i class="fa fa-save"></i> Grabar</button>
            </div>
        </div>
    </div>
</div>