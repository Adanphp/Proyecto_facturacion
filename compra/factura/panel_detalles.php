<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_fac = $_POST['id_fac'];
if($id_fac == '-1'){ //AL PRESIONAR CANCELAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_fac == '0'){ //AL PRESIONAR AGREGAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Guarde los datos de la cabecera...
</label>
<?php
}else{
    if($id_fac == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_factura WHERE id_fac = (select max(id_fac) from factura );"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_factura WHERE id_fac = $id_fac;"));
    }
    $detalles = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_factura_detalles WHERE id_fac = $id_fac;"));
    ?>

        <div class="card-body">
            <?php if(empty($detalles)){ ?>
            <?php }else{ ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio </th>
                       
                        
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detalles as $d){ ?>
                        <?php $total = 0; foreach($detalles as $d){ $total = $total + ($d['precio'] * $d['cantidad']); ?>
  
                    <tr>
                    
                        <td><?= $d['item_descrip'];?></td>
                        <td><?= $d['cantidad']; ?></td>
                        <td><?= $d['precio']; ?></td>
                        <td><?= $d['precio'] * $d['cantidad']; ?></td>
                        
                     <td>                       
                            <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
                                <button class="btn btn-warning text-white" onclick="modificar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" onclick="quitar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                     } ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2"><?php echo $total; ?></th>
                        </tr>
                    </tfoot>
            </table>
            <?php } ?>
        </div>
    </div>
    
    <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
        <div class="card-primary col-12">
            <div class="card-header">
                Añadir Producto
            </div>
            <div class="card-body">
                <?php $items = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_items WHERE id_item NOT IN (SELECT id_item FROM v_orden_detalles WHERE id_or = ".$cab[0]['id_or'].") ORDER BY item_descrip;")); ?>
                <?php if(empty($items)){ ?>
                    <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Ya no hay productos disponibles...</label>
                <?php }else{ ?>
    
               
                   
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="select2" id="agregar_id_item">
                            <?php foreach($items as $i){ ?>
                            <option value="<?= $i['id_item'];?>"><?= $i['item_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control" value="1" id="agregar_cantidad">
                    </div>
                    <div class="form-group">
                            <label>Precio</label>
                            <input type="number" value="1000" class="form-control" id="agregar_precio">
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