<script type="text/javascript">
    function verificar_mensaje(resultado){
        r = resultado.split('_/_');
        var texto = r[0];
        var tipo = r[1];
        if(tipo == 'error'){
            texto = texto.split('ERROR:');
            texto = texto[2];
        }
        if(tipo == 'success'){
            texto = texto.split('NOTICE:');
            texto = texto[1];
        }
        mensaje(texto, tipo);
        if(tipo == 'success'){
            return true;
        }else{
            return false;
        }
    }
function mensaje (texto, tipo){
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000
  });
  if(tipo == "" || tipo == null){
    tipo = 'warning';
  }
  Toast.fire({
    type: tipo,
    title: texto
  })
}
</script>
<?php if($_SESSION['mensaje'] != ''){ ?>
	<script type="text/javascript">
		mensaje('<?php echo $_SESSION['mensaje']; ?>','<?php echo $_SESSION['tipo_mensaje']; ?>');
	</script>
<?php } ?>