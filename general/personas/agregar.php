<?php
    require '../../conexion.php';
    require '../../session.php';
    $conexion = Conexion::conectar();
    $ciudades = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_ciudades WHERE estado = 'ACTIVO' ORDER BY ciu_descrip, pais_descrip;"));
    $estados_civiles = pg_fetch_all(pg_query($conexion, "SELECT * FROM estados_civiles WHERE estado = 'ACTIVO' ORDER BY id_ec;"));
    $generos = pg_fetch_all(pg_query($conexion, "SELECT * FROM generos WHERE estado = 'ACTIVO' ORDER BY gen_descrip;"));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card-success">
            <div class="card-header">
                Agregar Persona
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Persona Física <input type="checkbox" checked="checked" id="agregar_es_persona"></label>
                </div>
                <div class="form-group">
                    <label>C.I.</label>
                    <input type="text" class="form-control" id="agregar_per_ci">
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" id="agregar_per_nombre">
                </div>
                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" class="form-control" id="agregar_per_apellido">
                </div>
                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="agregar_per_fenaci" value="<?= (date('Y') - 18).date('-m-d');?>">
                </div>
                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" class="form-control" id="agregar_per_celular">
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="text" class="form-control" id="agregar_per_email">
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" class="form-control" id="agregar_per_direccion">
                </div>
                <div class="form-group">
                    <label>Ciudad de Nacimiento</label>
                    <select class="form-control select2" style="width: 100%;" id="agregar_id_ciu">
                        <?php foreach($ciudades as $c){ ?>
                            <option value="<?= $c['id_ciu']; ?>"><?= $c['ciu_descrip']." - ".$c['pais_descrip'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Estado Civil</label>
                    <select class="form-control select2" style="width: 100%;" id="agregar_id_ec">
                        <?php foreach($estados_civiles as $e){ ?>
                            <option value="<?= $e['id_ec']; ?>"><?= $e['ec_descrip'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Género</label>
                    <select class="form-control select2" style="width: 100%;" id="agregar_id_gen">
                        <?php foreach($generos as $g){ ?>
                            <option value="<?= $g['id_gen']; ?>"><?= $g['gen_descrip'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-danger" data-dismiss="modal" id="btn-modal-agregar-cerrar">
                    <i class="fa fa-ban"></i> Cancelar
                </button>
                <button class="btn btn-success" onclick="agregar_grabar();"><i class="fa fa-save"></i> Grabar</button>
            </div>
        </div>
    </div>
</div>