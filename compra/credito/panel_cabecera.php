<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_cre = $_POST['id_cre'];

if($id_cre == '-1'){ //AL PRESIONAR CANCELAR
    
?>

<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_cre == '0'){ //AL PRESIONAR AGREGAR
    
?>
<div class="card-success">
    <div class="card-header">
        Ordenes
    </div>
    <?php
    $id_tim = 'id_tim';
    $id_pro = 'id_pro';
    $id_doc = 'id_doc';
    $id_tip = 'id_tip';

    ?>
    

             <div class="card-body">
        <input type="hidden" id="id_cre" value="<?= $cab[0]['id_cre'];?>">
        <input type="hidden" id="eliminar_id_item" value="0">
  
    <?php    $proveedor = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_proveedor WHERE id_pro = $id_pro ORDER BY pro_descrip;"));
            ?>    <?php if(empty($proveedor)){ ?>
                    <?php } ?>
                    <?php    $timbrado = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_timbrado WHERE id_tim = $id_tim ORDER BY tim_descrip;"));
            ?>    <?php if(empty($timbrado)){ ?>
                    <?php } ?> 
                    <?php    $documentos = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_documentos WHERE id_doc = $id_doc ORDER BY doc_descri;"));
            ?>    <?php if(empty($documentos)){ ?>
                    <?php } ?>
                    <?php    $tipo_pago = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_tipo_pago WHERE id_tip = $id_tip ORDER BY tip_detalles;"));
            ?>    <?php if(empty($tigo_pago)){ ?>
                    <?php } ?>

                   
        
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= date('Y-m-d'); ?>" id="fecha">
        </div>

         <div class="form-group">
                            <label>N° De Comprobante</label>
                            <input type="number" value="" class="form-control" id="agregar_com">
                        </div>

        <div class="form-group">
                        <label>Timbrado</label>
                        <select class="select2" id="agregar_id_tim">
                            <?php foreach($timbrado as $t){ ?>
                            <option value="<?=  $t['id_tim'];?>"><?= $t['tim_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fabricante</label>
                        <select class="select2" id="agregar_id_pro">
                            <?php foreach($proveedor as $pr){ ?>
                            <option value="<?=  $pr['id_pro'];?>"><?= $pr['pro_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Documentos</label>
                        <select class="select2" id="agregar_id_doc">
                            <?php foreach($documentos as $d){ ?>
                            <option value="<?=  $d['id_doc'];?>"><?= $d['doc_descri']."";?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Pago</label>
                        <select class="select2" id="agregar_id_tip">
                            <?php foreach($tipo_pago as $ti){ ?>
                            <option value="<?=  $ti['id_tip'];?>"><?= $ti['tip_detalles']."";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
            <label>Intervalo Cuota</label>
            <input class="form-control" type="date" value="<?= date('Y-m-d'); ?>" id="agregar_intervalo_cuota">
        </div>

        <div class="btn-group">
            <button class="btn btn-danger" onclick="cancelar();">
                <i class="fa fa-ban"></i> Cancelar
            </button>
            <button class="btn btn-success" onclick="agregar_grabar();">
                <i class="fa fa-save"></i> Grabar
            </button>
        </div>
    </div>
</div>
<?php
}else{
    if($id_cre == true){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_credito WHERE id_cre = (select max(id_cre) from credito);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_credito WHERE id_cre = $id_cre;"));
        
    }
    if($cab[0]['estado'] == 'PENDIENTE'){ //PARA HABILITAR O DESHABILITAR CAMPOS
        $deshabilitado = "";
    }else{
        $deshabilitado = "disabled";
    } ?>
<div class="card-success">
    <div class="card-header">
        Datos del Pedido
    </div>
    
    <?php
    $id_tim = 'id_tim';
    $id_pro = 'id_pro';
    $id_doc = 'id_doc';
    $id_tip = 'id_tip';
    ?>
    <div class="card-body">
    <?php    $proveedor = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_proveedor WHERE id_pro = $id_pro ORDER BY pro_descrip;"));
            ?>    <?php if(empty($proveedor)){ ?>
                    <?php } ?>
                    <?php    $timbrado = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_timbrado WHERE id_tim = $id_tim ORDER BY tim_descrip;"));
            ?>    <?php if(empty($timbrado)){ ?>
                    <?php } ?> 
                    <?php    $documentos = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_documentos WHERE id_doc = $id_doc ORDER BY doc_descri;"));
            ?>    <?php if(empty($documentos)){ ?>
                    <?php } ?>
                    <?php    $tipo_pago = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_tipo_pago WHERE id_tip = $id_tip ORDER BY tip_detalles;"));
            ?>    <?php if(empty($tigo_pago)){ ?>
                    <?php } ?>

        
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="date" value="<?= date('Y-m-d'); ?>" id="fecha">
        </div>

         <div class="form-group">
                            <label>N° De Comprobante</label>
                            <input type="number" value="" class="form-control" id="agregar_com">
                        </div>

        <div class="form-group">
                        <label>Timbrado</label>
                        <select class="select2" id="agregar_id_tim">
                            <?php foreach($timbrado as $t){ ?>
                            <option value="<?=  $t['id_tim'];?>"><?= $t['tim_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fabricante</label>
                        <select class="select2" id="agregar_id_pro">
                            <?php foreach($proveedor as $pr){ ?>
                            <option value="<?=  $pr['id_pro'];?>"><?= $pr['pro_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Documentos</label>
                        <select class="select2" id="agregar_id_doc">
                            <?php foreach($documentos as $d){ ?>
                            <option value="<?=  $d['id_doc'];?>"><?= $d['doc_descri']."";?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Pago</label>
                        <select class="select2" id="agregar_id_tip">
                            <?php foreach($tipo_pago as $ti){ ?>
                            <option value="<?=  $ti['id_tip'];?>"><?= $ti['tip_detalles']."";?></option>
                            <?php } ?>
                        </select>
                    </div>    <div class="btn-group">
            <button class="btn btn-danger" onclick="cancelar();">
                <i class="fa fa-ban"></i> Cancelar
            </button>
            <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
            <button class="btn btn-success" onclick="confirmar();"><i class="fa fa-check-circle"></i> Confirmar</button>
            <button class="btn btn-warning text-white" onclick="modificar();"><i class="fa fa-edit"></i> Modificar</button>
            <button class="btn btn-danger" onclick="anular();"><i class="fa fa-minus-circle"></i> Anular</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php
} ?>