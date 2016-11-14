<h1>Inscrição Realizada</h1>
<div class="col-sm-10 col-sm-offset-1">
    <p class="text-center">Sua inscrição foi concluída, agora é só efetuar o pagamento e aguardar a confirmação via e-mail.</p>
    <blockquote>
        <p><?=$member->getNome()?></p>
         <small class="member">
            <?=$member->getCapitulo() ? "<i class=\"flaticon-house\"></i> ".$member->getCapitulo()."<br />" : ""?>
            <?=$member->getConvento() ? "<i class=\"flaticon-circus\"></i> ".$member->getConvento()."<br />" : ""?>
            <?=$member->getMacom() ? "<i class=\"flaticon-compass-1\"></i> ".$member->getMacom()."<br />" : ""?>

                <i class="flaticon-id-card"></i> <?=$member->getCid()?>
                <i class="flaticon-birthday-cake"></i> <?=$member->getDataNascimento()?>
            <br />
                <i class="flaticon-back"></i> <?=$member->getEmail()?> 
                <i class="flaticon-technology"></i> <?=$member->getTelefone()?>
            <br />
                <i class="flaticon-clothes"></i> Camiseta: <?=$member->getTamanhoCamiseta()?> 
                <i class="flaticon-diploma"></i> Grau Pretendido: <?=$member->getGrauDesejado()?>
        </small>
    </blockquote>
    <h4>Método de Pagamento:</h4>
    <div class="select">
        <div class="col-sm-12">
            <?php 
            if($link_cartao && $link_cartao != "") { ?>
            <div class="alert alert-info">
              <strong>Atenção!</strong> Clique no link abaixo para inserir seu Cartão no ambiente seguro. A inscrição somente será confirmada após o devido pagamento ser autorizado pelo banco.
            </div>
            
            <a href="<?=$link_cartao?>" target="_blank" class="inputButton">Cadastrar Cartão</a>
            
            <?php } else { ?>
            <div class="alert alert-info">
                Acesse a área abaixo para verificar o pagamento de sua inscrição.
            </div>
            
            <a href="https://pansani-001.superlogica.net/clients/areadocliente?token=<?=$token?>" target="_blank" class="inputButton">Acessar Área do Cliente</a>
            <?php } ?>
        </div>
    <p class="text-center"><small>A confirmação pode levar até 72 horas após o pagamento.</small></p>
    </div>
</div>
