<?php
    require '../../conexion.php';
    $conexion = Conexion::conectar();
    $personas = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_personas;"));
?>
<table class="table table-bordered" id="tabla_datos">
    <thead>
        <tr>
            <th>#</th>
            <th>Tipo de Persona</th>
            <th>C.I.</th>
            <th>R.U.C.</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Nacimiento</th>
            <th>Celular</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Ciudad</th>
            <th>Nacionalidad</th>
            <th>Estado Civil</th>
            <th>Género</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($personas)){ foreach($personas as $p){ ?>
        <tr>
            <td><?= $p['id_per']; ?></td>
            <td><?= $p['tipo_persona']; ?></td>
            <td><?= $p['per_ci']; ?></td>
            <td><?= $p['per_ruc']; ?></td>
            <td><?= $p['per_nombre']; ?></td>
            <td><?= $p['per_apellido']; ?></td>
            <td><?= $p['per_fenaci']; ?></td>
            <td><?= $p['per_celular']; ?></td>
            <td><?= $p['per_email']; ?></td>
            <td><?= $p['per_direccion']; ?></td>
            <td><?= $p['ciu_descrip']; ?></td>
            <td><?= $p['gentilicio']; ?></td>
            <td><?= $p['ec_descrip']; ?></td>
            <td><?= $p['gen_descrip']; ?></td>
            <td><?= $p['estado']; ?></td>
            <td>
                <?php if($p['estado'] == 'ACTIVO'){ ?>
                    <button class="btn btn-danger" title="Inactivar" onclick="inactivar(<?= $p['id_per'];?>);"><i class="fa fa-minus-circle"></i></button>
                    <button class="btn btn-warning text-white" title="Editar" onclick="editar(<?= $p['id_per'];?>);"><i class="fa fa-edit"></i></button>
                <?php }else{ ?>
                    <button class="btn btn-success" title="Activar" onclick="activar(<?= $p['id_per'];?>);"><i class="fa fa-check-circle"></i></button>
                <?php } ?>
            </td>
        </tr>
        <?php } } ?>
    </tbody>
</table>