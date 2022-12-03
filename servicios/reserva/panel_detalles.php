<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_re = $_POST['id_re'];
if($id_re == '-1'){ //AL PRESIONAR CANCELAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_re == '0'){ //AL PRESIONAR AGREGAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Guarde los datos de la cabecera...
</label>
<?php
}else{
    if($id_re == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_reserva WHERE id_re = (select max(id_re) from reserva);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_reserva WHERE id_re = $id_re;"));
    }
    $detalles = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_reserva_detalles WHERE id_re = $id_re;"));
    ?>
<div class="row">
    <div class="card-success col-8">
        <div class="card-header">
            Detalles Cargados
        </div>
        <div class="card-body">
            <?php if(empty($detalles)){ ?>
            <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Sin detalles cargados...</label>
            <?php }else{ ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tipo de Servicio</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detalles as $d){ ?>
                    <tr>
                        <td><?= $d['tp_servicio_descri']; ?></td>
                        <td><?= $d['item_descrip']."(".$d['mar_descrip'].")"; ?></td>
                        <td><?= $d['cantidad']; ?></td>
                        <td>
                            <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
                                <button class="btn btn-warning text-white" onclick="editar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" onclick="quitar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
    <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
        <div class="card-info col-4">
            <div class="card-header">
                Añadir Producto
            </div>
            <div class="card-body">
                <?php $items = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_items WHERE id_item NOT IN (SELECT id_item FROM reserva_detalles WHERE id_re = ".$cab[0]['id_re'].") ORDER BY item_descrip, mar_descrip;")); ?>
                <?php if(empty($items)){ ?>
                    <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Ya no hay productos disponibles...</label>
                <?php }else{ ?>
                    <?php $tipo_servicios = pg_fetch_all(pg_query($conexion, "SELECT * FROM tipo_servicios WHERE id_tipo_servicio NOT IN (SELECT id_tipo_servicio FROM reserva_detalles WHERE id_re = ".$cab[0]['id_re'].") ORDER BY tp_servicio_descri;")); ?>
                <?php if(empty($tipo_servicios)){ ?>
                    <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Ya no hay servicios disponibles...</label>
                <?php }else{ ?>
                    <div class="form-group">
                    <label>Tipo de Servicio</label>
                        <select class="select2" id="agregar_id_tipo_servicio">
                            <?php foreach($tipo_servicios as $tp){ ?>
                            <option value="<?= $tp['id_tipo_servicio'];?>"><?= $tp['tp_servicio_descri']."";?></option>
                            <?php } ?>
                        </select>
                </div>
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="select2" id="agregar_id_item">
                            <?php foreach($items as $i){ ?>
                            <option value="<?= $i['id_item'];?>"><?= $i['item_descrip']." (".$i['mar_descrip'].")";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control" value="1" id="agregar_cantidad">
                    </div>
                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" class="form-control" value="1" id="agregar_precio">
                        
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success" onclick="agregar_detalle();"><i class="fa fa-plus-circle"></i> Agregar Producto</button>
                    </div>
              <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
} ?>
<?php
} ?>
