<?php
$id_cla = $_POST['id_cla'];
require '../../conexion.php';
$conexion = Conexion::conectar();
$cla = pg_fetch_all(pg_query($conexion, "SELECT * FROM clasificaciones WHERE id_cla = $id_cla;"));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card-warning">
            <div class="card-header text-center text-white">
                EDITAR
            </div>
            <div class="card-body">
                <input type="hidden" id="editar_id_pais" value="<?= $cla[0]['id_cla']; ?>">
                <div class="form-group">
                    <label>Descripción</label>
                    <input type="text" class="form-control" id="editar_pais_descrip" value="<?= $cla[0]['cla_descrip']; ?>">
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

