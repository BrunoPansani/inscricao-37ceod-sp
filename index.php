<?php
session_start();

require('./config.php');

// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate('csrf_token');

require('./template/header.php');
$open = false;
if($open == true) {
?>

			<form id="inscricao" method="post" action="./confirmacao">
				<h1>Inscreva-se</h1>
				<p class="text-center"><small>Se você é um membro externo ao Supremo Conselho da Ordem DeMolay para o Brasil <a href="./externo">clique aqui.</a></small></p>	
				<input name="cid" type="number" placeholder="Digite sua CID" required="" min="1" class="input input-cid pass">
				<input type="hidden" name="csrf_token" value="<?=$token;?>">
				<input type="submit" value="Confirmar" class="inputButton">
			</form>
			
			

<?php 
} else {
?>
<h1>Inscrições Encerradas</h1>
<p class="text-center">As inscrições para o 37º Congresso Estadual da Ordem DeMolay estão encerradas. Esperamos você no dia 19 e 20 de novembro da capital paulista.<br>
    <a href="/index.html">Clique aqui</a> para voltar para o site o 37º CEOD.</p>	
				
    <?php
    
    
    
}
require('./template/footer.php');

// Mostra todas as informações, usa o padrão INFO_ALL
//phpinfo();

?>
<script>
$(window).load(function() {
    //$('#precos').modal('show');
});
	
</script>