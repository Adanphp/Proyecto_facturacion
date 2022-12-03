$(function () {
    panel_credito();
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

function panel_credito(){
    $.ajax({
        url:"panel_credito.php"
    }).done(function(resultado){
        $("#panel-credito").html(resultado);
        formato_tabla("#tabla_panel_credito", 5);
    });
}

function panel_cabecera(id_cre){
    $.ajax({
        url:"panel_cabecera.php",
        method:"POST",
        data:{
            id_cre:id_cre
        }
    }).done(function(resultado){
        $("#panel-cabecera").html(resultado);
    });
}

function panel_detalles(id_cre){
    $.ajax({
        url:"panel_detalles.php",
        method:"POST",
        data:{
            id_cre:id_cre
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
    panel_credito();
    $("#btn-panel-credito").click();
    mensaje('CANCELADO','error');
}

function cabecera(id_cre){
    panel_cabecera(id_cre);
    panel_detalles(id_cre);
    $("#btn-panel-cabecera").click();
}

function detalles(id_cre){
    panel_cabecera(id_cre);
    panel_detalles(id_cre);
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

function modificar_detalle(id_item){
    var id_cre = $("#id_cre").val();
    $.ajax({
        type:"POST",
        url:"panel_modificar.php",
        data:{
            id_cre: id_cre,
            id_item: id_item
        }
    }).done(function(resultado){
        $("#panel-modificar").html(resultado);
        $("#btn-panel-modificar").click();
    });
}

function modificar_detalle_grabar(){
    $("#operacion").val(6);
    grabar();
}

function grabar(){
    var id_cre = '0';
    var fecha = '2021-01-01';
    var com = '0';
    var id_tim = '0';
    var id_doc = '0';
    var id_pro = '0';
    var id_tip = '0';
    var intervalo_cuota = '2021-01-01';
    var id_item = '0';
    var cantidad = '0';
    var precio = '0';
    var iva = '0';
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        fecha = $("#fecha").val();

    }
    if(operacion == '2' || operacion == '3' || operacion == '4'){
        id_cre = $("#id_cre").val();
        fecha = $("#fecha").val();
        com = $("#agregar_com").val();
        id_tim = $("#agregar_id_tim").val();
        id_doc = $("#agregar_id_doc").val();
        id_pro = $("#agregar_id_pro").val();
        id_tip = $("#gregar_id_tip").val();
        intervalo_cuota = $("#agregar_intervalo_cuota").val();
    }
    if(operacion == '5'){
        id_cre = $("#id_cre").val();
        id_item = $("#agregar_id_item").val();
        cantidad = $("#agregar_cantidad").val();
        precio = $("#agregar_precio").val();
        iva = $("#agregar_iva").val();
    }
    if(operacion == '6'){
        id_cre = $("#id_cre").val();
        id_pro =$("#modificar_id_pro").val();
        id_item = $("#modificar_id_item").val();
        cantidad = $("#modificar_cantidad").val();
        precio = $("#modificar_precio").val();
      
    }
    if(operacion == '7'){
        id_cre = $("#id_cre").val();
        id_item = $("#eliminar_id_item").val();
    }
    $.ajax({
        type:"POST",
        url:"grabar.php",
        data:{
            id_cre:id_cre,
            fecha:fecha,
            com:com,
            id_tim:id_tim,
            id_doc:id_doc,
            id_pro:id_pro,
            id_tip:id_tip,
            intervalo_cuota:intervalo_cuota,
            id_item:id_item,
            cantidad:cantidad,
            precio:precio,
            iva:iva,
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
        panel_credito();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '2'){
        panel_cabecera($("#id_cre").val());
        panel_detalles($("#id_cre").val());
        panel_credito();
        $("#btn-panel-credito").click();
    }
    if(operacion == '3' || operacion == '4'){
        panel_cabecera(-1);
        panel_detalles(-1);
        panel_credito();
        $("#btn-panel-credito").click();
    }
    if(operacion == '5' || operacion == '6' || operacion == '7'){
        panel_cabecera($("#id_cre").val());
        panel_detalles($("#id_cre").val());
    }
    if(operacion == '6'){
        $("#btn-modal-modificar-cerrar").click();
    }
}