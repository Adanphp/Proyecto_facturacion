<?php
require '../../conexion.php';
require '../../session.php';
$id_ajus = $_POST['id_ajus'];
$id_item = $_POST['id_item'];
$conexion = Conexion::conectar();
$d = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_ajustes_detalles WHERE id_ajus = $id_ajus AND id_item = $id_item;"));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card-warning">
            <div class="card-header text-white">
                Editar Detalle
            </div>
            <div class="card-body">
                <input type="hidden" id="modificar_id_item" value="<?= $d[0]['id_item'];?>">
                <div class="form-group">
                    <label>Producto</label>
                    <input type="text" disabled="" class="form-control" value="<?= $d[0]['item_descrip']."";?>">
                </div>
                <div class="form-group">
                    <label>Cantidad Actual</label>
                    <input type="number" class="form-control" value="<?= $d[0]['actual'];?>" id="modificar_actual">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-danger" data-dismiss="modal" id="btn-modal-modificar-cerrar">
                    <i class="fa fa-ban"></i> Cancelar
                </button>
                <button class="btn btn-success" onclick="modificar_detalle_grabar();">
                    <i class="fa fa-save"></i> Grabar
                </button>
            </div>
        </div>
    </div>
</div>    