<form id="inscricao" method="post" action="./finaliza">
    <h1>Sua inscrição</h1>
    <div class="col-sm-10 col-sm-offset-1">
        <blockquote>
            <p><?=$knownMember->getNome()?></p>
            <small class="member">
                <?=$knownMember->getCapitulo() ? "<i class=\"flaticon-house\"></i> ".$knownMember->getCapitulo()."<br />" : ""?>
                <?=$knownMember->getConvento() ? "<i class=\"flaticon-circus\"></i> ".$knownMember->getConvento()."<br />" : ""?>
                <?=$knownMember->getMacom() ? "<i class=\"flaticon-compass-1\"></i> ".$knownMember->getMacom()."<br />" : ""?>
                    <i class="flaticon-id-card"></i> <?=$knownMember->getCid()?>, <?=$knownMember->isRegular() ? "Regular" : "Irregular"?> 
                    <i class="flaticon-birthday-cake"></i> <?=$knownMember->getDataNascimento()?>
                <br />
                    <i class="flaticon-back"></i> <?=$knownMember->getEmail()?> 
                    <i class="flaticon-technology"></i> <?=$knownMember->getTelefone()?>
                <br />
                    <i class="flaticon-clothes"></i> Camiseta: <?=$knownMember->getTamanhoCamiseta()?> 
                    <i class="flaticon-diploma"></i> Grau Pretendido: <?=$knownMember->getGrauDesejado()?>
            </small>
        </blockquote>
        <?php
        include "payment_select.php";
        ?>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$token;?>">
    <input type="submit" value="Finalizar Inscrição" class="inputButton">
</form>