
$(function(){
    datos();
    $('body').on("keydown", function(e) {
        if (e.ctrlKey && e.which === 81) {
            agregar();
        }
        //$("#tecla_presionada").val(e.keyCode);
    });
});

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
        $("#btn-modal-agregar").click();
    });
}

function agregar_grabar(){
    $("#operacion").val(1);
    grabar();
}

function editar(id_pais){
    $.ajax({
        type:"POST",
        url:"editar.php",
        data:{
            id_pais:id_pais
        }
    }).done(function(resultado){
        $("#modal-editar").html(resultado);
        $("#btn-modal-editar").click();
        $('#editar_pais_abreviatura').keypress(function(e) {
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if(key==13){
                editar_grabar();
            }
        });
    });
}

function editar_grabar(){
    $("#operacion").val(2);
    grabar();
}

function activar(id_pais){
    $("#id_pais").val(id_pais);
    $("#operacion").val(3);
    grabar();
}

function inactivar(id_pais){
    $("#id_pais").val(id_pais);
    $("#operacion").val(4);
    grabar();
}

function grabar(){
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        var id_pais = 0;
        var pais_descrip = $("#agregar_pais_descrip").val();
        var gentilicio = $("#agregar_gentilicio").val();
        var pais_abreviatura = $("#agregar_pais_abreviatura").val();
    }
    if(operacion == '2'){
        var id_pais = $("#editar_id_pais").val();
        var pais_descrip = $("#editar_pais_descrip").val();
        var gentilicio = $("#editar_gentilicio").val();
        var pais_abreviatura = $("#editar_pais_abreviatura").val();
    }
    if(operacion == '3' || operacion == '4'){
        var id_pais = $("#id_pais").val();
        var pais_descrip = '';
        var gentilicio = '';
        var pais_abreviatura = '';
    }
    $.ajax({
        type: "POST",
        url: "grabar.php",
        data: {
            id_pais: id_pais,
            pais_descrip: pais_descrip,
            gentilicio: gentilicio,
            pais_abreviatura: pais_abreviatura,
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