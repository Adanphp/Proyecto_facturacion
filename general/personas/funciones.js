
$(function(){
    datos();
    $('body').on("keydown", function(e) {
        if (e.ctrlKey && e.which === 81) {
            agregar();
        }
        //$("#tecla_presionada").val(e.keyCode);
    });
});

function refrescar_select(){
    $('.select2').select2();
}

function datos(){
    $.ajax({
        type:"POST",
        url:"datos.php"
    }).done(function(resultado){
        $("#div_datos").html(resultado);
        formato_tabla("#tabla_datos", 5);
    });
}

function formato_tabla(tabla, cantidad){
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

function agregar(){
    //$("#btn-modal-editar-cerrar").click();
    $.ajax({
        type:"POST",
        url:"agregar.php"
    }).done(function(resultado){
        $("#modal-agregar").html(resultado);
        refrescar_select();
        $("#btn-modal-agregar").click();
    });
}

function agregar_grabar(){
    $("#operacion").val(1);
    grabar();
}

function editar(id_per){
    $.ajax({
        type:"POST",
        url:"editar.php",
        data:{
            id_per:id_per
        }
    }).done(function(resultado){
        $("#modal-editar").html(resultado);
        refrescar_select();
        $("#btn-modal-editar").click();
    });
}

function editar_grabar(){
    $("#operacion").val(2);
    grabar();
}

function activar(id_per){
    $("#id_per").val(id_per);
    $("#operacion").val(3);
    grabar();
}

function inactivar(id_per){
    $("#id_per").val(id_per);
    $("#operacion").val(4);
    grabar();
}

function grabar(){
    var id_per = '0';
    var per_ci = '';
    var per_ruc = '';
    var per_nombre = '';
    var per_apellido = '';
    var per_fenaci = '2020-01-01';
    var per_celular = '';
    var per_email = '';
    var per_direccion = '';
    var id_ciu = '0';
    var id_gen = '0';
    var id_ec = '0';
    var es_persona = 't';
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        per_ci = $("#agregar_per_ci").val();
        per_ruc = $("#agregar_per_ruc").val();
        per_nombre = $("#agregar_per_nombre").val();
        per_apellido = $("#agregar_per_apellido").val();
        per_fenaci = $("#agregar_per_fenaci").val();
        per_celular = $("#agregar_per_celular").val();
        per_email = $("#agregar_per_email").val();
        per_direccion = $("#agregar_per_direccion").val();
        id_ciu = $("#agregar_id_ciu").val();
        id_gen = $("#agregar_id_gen").val();
        id_ec = $("#agregar_id_ec").val();
        if($("#agregar_es_persona").is(':checked')){
            es_persona = 't';
        }else{
            es_persona = 'f';
        }
    }
    if(operacion == '2'){
        id_per = $("#editar_id_per").val();
        per_ci = $("#editar_per_ci").val();
        per_ruc = $("#editar_per_ruc").val();
        per_nombre = $("#editar_per_nombre").val();
        per_apellido = $("#editar_per_apellido").val();
        per_fenaci = $("#editar_per_fenaci").val();
        per_celular = $("#editar_per_celular").val();
        per_email = $("#editar_per_email").val();
        per_direccion = $("#editar_per_direccion").val();
        id_ciu = $("#editar_id_ciu").val();
        id_gen = $("#editar_id_gen").val();
        id_ec = $("#editar_id_ec").val();
        if($("#editar_es_persona").is(':checked')){
            es_persona = 't';
        }else{
            es_persona = 'f';
        }
    }
    if(operacion == '3' || operacion == '4'){
        id_per = $("#id_per").val();
    }
    $.ajax({
        type:"POST",
        url:"grabar.php",
        data:{
            id_per: id_per,
            per_ci: per_ci,
            per_ruc: per_ruc,
            per_nombre: per_nombre,
            per_apellido: per_apellido,
            per_fenaci: per_fenaci,
            per_celular: per_celular,
            per_email: per_email,
            per_direccion: per_direccion,
            id_ciu: id_ciu,
            id_gen: id_gen,
            id_ec: id_ec,
            es_persona: es_persona,
            operacion: operacion
        }
    }).done(function(resultado){
        if(verificar_mensaje(resultado)){
            postgrabar();
        }
    });
}

function postgrabar(){
    var operacion = $("#operacion").val();
    datos();
    if(operacion == '1'){
        $("#btn-modal-agregar-cerrar").click();
    }
    if(operacion == '2'){
        $("#btn-modal-editar-cerrar").click();
    }
}