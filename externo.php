<?php
session_start();

require('./config.php');

// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate('csrf_token');

require('./template/header.php');
$open = false;

if($open == true) {
?>

	<form id="inscricao" method="post" action="./confirmacao_externa">
		<h1>Inscreva-se</h1>

		<div class="col-sm-12">
			<input name="nome" type="text" placeholder="Digite seu nome" required="" class="input input-cid pass">
			<input name="email" id="mail_field" type="email" placeholder="Digite seu e-mail" required="" class="input input-cid pass">
			<input name="celular" id="cel_field" type="phone" placeholder="Digite seu celular" required="" class="input input-cid pass">

		</div>
		<div class="select">
			<div class="col-sm-10 col-sm-offset-1">
				<div class="col-sm-12">

                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input name="data_nascimento" type="date" placeholder="Insira sua Data de Nascimento" required="" class="input pass">

					<label for="camiseta">Tamanho de Camiseta:</label>
					<select id="camiseta" name="camiseta" required="">
	                    <option value="" selected>Selecione um tamanho</option>
	                    <option>P</option>
	                    <option>M</option>
	                    <option>G</option>
	                    <option>GG</option>
	                </select>
				</div>
				<br />
				<?php
			        include "./template/payment_select.php";
			    ?>
			</div>
		</div>
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
