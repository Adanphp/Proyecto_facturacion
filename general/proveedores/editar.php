<?php
$id_proveedor = $_POST['id_proveedor'];
require '../../conexion.php';
$conexion = Conexion::conectar();
$proveedor = pg_fetch_all(pg_query($conexion, "SELECT * FROM proveedores WHERE id_proveedor = $id_proveedor;"));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card-warning">
            <div class="card-header text-center text-white">
                EDITAR
            </div>
            <div class="card-body">
                <input type="hidden" id="editar_id_proveedor" value="<?= $proveedor[0]['id_proveedor']; ?>">
                <div class="form-group">
                    <label>Proveedor</label>
                    <input type="text" class="form-control" id="editar_pro_descrip" value="<?= $proveedor[0]['pro_descrip']; ?>">
                </div>
              
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-danger" data-dismiss="modal" id="btn-modal-editar-cerrar">
                    <i class="fa fa-ban"></i> Cancelar
                </button>
                <button class="btn btn-success" onclick="editar_grabar();"><i class="fa fa-save"></i> Grabar</button>
            </div>
        </div>
    </div>
</div>




