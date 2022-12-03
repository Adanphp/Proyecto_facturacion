
<?php
$id_car= $_POST['id_car'];
require '../../conexion.php';
$conexion = Conexion::conectar();
$cargos = pg_fetch_all(pg_query($conexion, "SELECT * FROM cargos where id_car = $id_car"));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card-warning">
            <div class="card-header text-center text-white">
                EDITAR
            </div>
            <div class="card-body">
                <input type="hidden" id="editar_id_car" value="<?= $cargos[0]['id_car']?>">
                <div class="form-group">
                    <label>Descripcion</label>
                    <input type="text" class="form-control" id="editar_car_descrip" value="<?= $cargos[0]['car_descrip']?>">    
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-danger" data-dismiss="modal" id="btn-modal-editar-cerrar"><i class="fa fa-ban"></i>Cancelar</button>
                <button class="btn btn-success" onclick="editar_grabar();"><i class="fa fa-save"></i> Grabar</button>
            </div>
        </div>        
    </div>
</div>