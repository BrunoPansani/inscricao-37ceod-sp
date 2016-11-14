<h1>Inscrição Confirmada</h1>
<div class="col-sm-10 col-sm-offset-1">
    <div class="alert alert-success">
          <strong>Inscrição Confirmada!</strong> Parabéns, sua inscrição está confirmada para o 37º CEOD. A capital paulista lhe aguarda de braços abertos!
        </div>
    <blockquote>
        <p><?=$member->getNome()?></p>
         <small class="member">
            <?=$member->getCapitulo() ? "<i class=\"flaticon-house\"></i> ".$member->getCapitulo()."<br />" : ""?>
            <?=$member->getConvento() ? "<i class=\"flaticon-circus\"></i> ".$member->getConvento()."<br />" : ""?>
            <?=$member->getMacom() ? "<i class=\"flaticon-compass-1\"></i> ".$member->getMacom()."<br />" : ""?>

                <i class="flaticon-id-card"></i> <?=$member->getCid()?>, <?=$member->isRegular() ? "Regular" : "Irregular"?>
                <i class="flaticon-birthday-cake"></i> <?=$member->getDataNascimento()?>
            <br />
                <i class="flaticon-back"></i> <?=$member->getEmail()?> 
                <i class="flaticon-technology"></i> <?=$member->getTelefone()?>
            <br />
                <i class="flaticon-clothes"></i> Camiseta: <?=$member->getTamanhoCamiseta()?> 
                <i class="flaticon-diploma"></i> Grau Pretendido: <?=$member->getGrauDesejado()?>
        </small>
    </blockquote>
</div>
