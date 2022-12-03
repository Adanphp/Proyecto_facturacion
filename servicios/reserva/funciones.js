$(function () {
    panel_reserva();
});

function refrescar_select(){
    $(".select2").select2();
    $(".select2").attr("style", "width: 100%;");
}

function formato_tabla(tabla,cantidad){
    $(tabla).DataTable({
        "lengthChange": false,
        responsive: "true",
        "iDisplayLength": cantidad,
        language: {
            "sSearch":"Buscar: ",
            "sInfo":"Mostrando resultados del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoFiltered": "(filtrado de entre _MAX_ registros)",
            "sZeroRecords":"No hay resultados",
            "sInfoEmpty":"No hay resultados",
            "oPaginate":{
                "sNext":"Siguiente",
                "sPrevious":"Anterior"
            }
        }
    });
}

function panel_reserva(){
    $.ajax({
        url:"panel_reserva.php"
    }).done(function(resultado){
        $("#panel-reserva").html(resultado);
        formato_tabla("#tabla_panel_reserva", 5);
    });
}

function panel_cabecera(id_re){
    $.ajax({
        url:"panel_cabecera.php",
        method:"POST",
        data:{
            id_re:id_re
        }
    }).done(function(resultado){
        $("#panel-cabecera").html(resultado);
    });
}

function panel_detalles(id_re){
    $.ajax({
        url:"panel_detalles.php",
        method:"POST",
        data:{
            id_re:id_re
        }
    }).done(function(resultado){
        $("#panel-detalles").html(resultado);
        refrescar_select();
    });
}

function agregar(){
    panel_cabecera(0);
    panel_detalles(0);
    $("#btn-panel-cabecera").click();
}

function cancelar(){
    panel_cabecera(-1);
    panel_detalles(-1);
    panel_reserva();
    $("#btn-panel-reserva").click();
    mensaje('CANCELADO','error');
}

function cabecera(id_re){
    panel_cabecera(id_re);
    panel_detalles(id_re);
    $("#btn-panel-cabecera").click();
}

function detalles(id_re){
    panel_cabecera(id_re);
    panel_detalles(id_re);
    $("#btn-panel-detalles").click();
}

function agregar_grabar(){
    $("#operacion").val(1);
    grabar();
}

function modificar(){
    $("#operacion").val(2);
    grabar();
}

function confirmar(){
    $("#operacion").val(3);
    grabar();
}

function anular(){
    $("#operacion").val(4);
    grabar();
}

function agregar_detalle(){
    $("#operacion").val(5);
    grabar();
}

function quitar_detalle(id_item){
    $("#eliminar_id_item").val(id_item);
    $("#operacion").val(7);
    grabar();
}

function editar_detalle(id_item){
    var id_re = $("#id_re").val();
    $.ajax({
        type:"POST",
        url:"panel_editar.php",
        data:{
            id_re: id_re,
            id_item: id_item
        }
    }).done(function(resultado){
        $("#panel-editar").html(resultado);
        $("#btn-panel-editar").click();
    });
}

function editar_detalle_grabar(){
    $("#operacion").val(6);
    grabar();
}

function grabar(){
    var id_re = '0';
    var ped_fecha = '2021-01-01';
    var id_tipo_servicio = '0';
    var id_item = '0';
    var cantidad = '0';
    var precio = '0';
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        ped_fecha = $("#ped_fecha").val();
    }
    if(operacion == '2' || operacion == '3' || operacion == '4'){
        id_re = $("#id_re").val();
        ped_fecha = $("#ped_fecha").val();
    }
    if(operacion == '5'){
        id_re = $("#id_re").val();
        id_tipo_servicio = $("#agregar_id_tipo_servicio").val();
        id_item = $("#agregar_id_item").val();
        cantidad = $("#agregar_cantidad").val();
        precio = $("#agregar_precio").val();
    }
    if(operacion == '6'){
        id_re = $("#id_re").val();
        id_item = $("#modificar_id_item").val();
        cantidad = $("#modificar_cantidad").val();
        precio = $("#modficiar_precio").val();
    }
    if(operacion == '7'){
        id_re = $("#id_re").val();
        id_item = $("#eliminar_id_item").val();
    }
    $.ajax({
        type:"POST",
        url:"grabar.php",
        data:{
            id_re:id_re,
            ped_fecha:ped_fecha,
            id_tipo_servicio:id_tipo_servicio,
            id_item:id_item,
            cantidad:cantidad,
            precio:precio,
            operacion:operacion
        }
    }).done(function(resultado){
        if(verificar_mensaje(resultado)){
            postgrabar();
        }
    });
}

function postgrabar(){
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        panel_cabecera(-2);
        panel_detalles(-2);
        panel_reserva();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '2'){
        panel_cabecera($("#id_re").val());
        panel_detalles($("#id_re").val());
        panel_reserva();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '3' || operacion == '4'){
        panel_cabecera(-1);
        panel_detalles(-1);
        panel_reserva();
        $("#btn-panel-reserva").click();
    }
    if(operacion == '5' || operacion == '6' || operacion == '7'){
        panel_cabecera($("#id_re").val());
        panel_detalles($("#id_re").val());
    }
    if(operacion == '6'){
        $("#btn-modal-editar-cerrar").click();
    }
}

