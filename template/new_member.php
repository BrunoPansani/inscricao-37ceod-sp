<form id="inscricao" method="post" action="./finaliza">
    <h1>Quase lá...</h1>
    <div class="col-sm-10 col-sm-offset-1">
        <blockquote>
            <p><?=$newMember->getNome()?></p>
            <small class="member">
                <?=$newMember->getCapitulo() ? "<i class=\"flaticon-house\"></i> ".$newMember->getCapitulo()."<br />" : ""?>
                <?=$newMember->getConvento() ? "<i class=\"flaticon-circus\"></i> ".$newMember->getConvento()."<br />" : ""?>
                <?=$newMember->getMacom() ? "<i class=\"flaticon-compass-1\"></i> ".$newMember->getMacom()."<br />" : ""?>

                    <i class="flaticon-id-card"></i> <?=$newMember->getCid()?>, <?=$newMember->isRegular() ? "Regular" : "Irregular"?>
                <br />
                    <i class="flaticon-birthday-cake"></i> <?=$newMember->getDataNascimento()?>
            </small>
        </blockquote>
        <h4>Confirme alguns dados:</h4>
        <div class="col-sm-12">
            <input name="email" id="mail_field" type="email" placeholder="Digite seu e-mail" required="" class="input pass" value="<?=$newMember->getEmail();?>">
            <input name="celular" id="cel_field" type="phone" placeholder="Digite seu celular" required="" class="input pass" value="<?=$newMember->getTelefone();?>">
        </div>
        <div class="select">
            <div class="col-sm-6">
                <label for="camiseta">Tamanho de Camiseta:</label>
                <select id="camiseta" name="camiseta" required="">
                    <option value="" selected>Selecione um tamanho</option>
                    <option>P</option>
                    <option>M</option>
                    <option>G</option>
                    <option>GG</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label for="grau">Grau da Cavalaria:</label>
                <select id="grau" name="grau" required=""<?=$newMember->getMacom() == "Sim" ? "" : ""?>>
                    <option value="Nenhum" selected>Nenhum</option>
                    <option value="Cadência">Cadência</option>
                </select>
            </div>
        </div>
        <br />
        <?php
        include "payment_select.php";
        ?>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$token;?>">
    <input type="submit" value="Finalizar Inscrição" class="inputButton">
</form>