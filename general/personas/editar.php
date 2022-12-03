<?php
$id_per = $_POST['id_per'];
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$p = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_personas WHERE id_per = $id_per;"));
$ciudades = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_ciudades WHERE id_ciu != ".$p[0]['id_ciu']));
$estados_civiles = pg_fetch_all(pg_query($conexion, "SELECT * FROM estados_civiles WHERE id_ec != ".$p[0]['id_ec']));
$generos = pg_fetch_all(pg_query($conexion, "SELECT * FROM generos WHERE id_gen != ".$p[0]['id_gen']));
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="card-warning">
            <div class="card-header text-center text-white">
                Modificar Persona
            </div>
            <div class="card-body">
                <input type="hidden" id="editar_id_per" value="<?= $p[0]['id_per'];?>">
                <div class="form-group">
                    <label>Persona Física 
                        <input type="checkbox" <?php if($p[0]['tipo_persona'] == 'FISICA'){ echo "checked"; } ?> id="editar_es_persona">
                    </label>
                </div>
                <div class="form-group">
                    <label>C.I.</label>
                    <input type="text" class="form-control" id="editar_per_ci" value="<?= $p[0]['per_ci']; ?>">
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" class="form-control" id="editar_per_nombre" value="<?= $p[0]['per_nombre']; ?>">
                </div>
                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" class="form-control" id="editar_per_apellido" value="<?= $p[0]['per_apellido']; ?>">
                </div>
                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="editar_per_fenaci" value="<?= $p[0]['per_fenaci']; ?>">
                </div>
                <div class="form-group">
                    <label>Celular</label>
                    <input type="text" class="form-control" id="editar_per_celular" value="<?= $p[0]['per_celular']; ?>">
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="text" class="form-control" id="editar_per_email" value="<?= $p[0]['per_email']; ?>">
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" class="form-control" id="editar_per_direccion" value="<?= $p[0]['per_direccion']; ?>">
                </div>
                <div class="form-group">
                    <label>Ciudad de Nacimiento</label>
                    <select class="form-control select2" style="width: 100%;" id="editar_id_ciu">
                        <option value="<?= $p[0]['id_ciu']; ?>"><?= $p[0]['ciu_descrip']." - ".$p[0]['pais_descrip'];?></option>
                        <?php foreach($ciudades as $c){ ?>
                            <option value="<?= $c['id_ciu']; ?>"><?= $c['ciu_descrip']." - ".$c['pais_descrip'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Estado Civil</label>
                    <select class="form-control select2" style="width: 100%;" id="editar_id_ec">
                        <option value="<?= $p[0]['id_ec']; ?>"><?= $p[0]['ec_descrip'];?></option>
                        <?php foreach($estados_civiles as $e){ ?>
                            <option value="<?= $e['id_ec']; ?>"><?= $e['ec_descrip'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Género</label>
                    <select class="form-control select2" style="width: 100%;" id="editar_id_gen">
                        <option value="<?= $p[0]['id_gen']; ?>"><?= $p[0]['gen_descrip'];?></option>
                        <?php foreach($generos as $g){ ?>
                            <option value="<?= $g['id_gen']; ?>"><?= $g['gen_descrip'];?></option>
                        <?php } ?>
                    </select>
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