<?php
require('./config.php');

require('./inc/superlogica_api.php');
$template = "";
if (isset($_GET["time"]) && isset($_GET["csrf"]))
{
    $token = isset($_GET["csrf"]);
   
    $superApi = new SLAPIHelper();  

    if(isset($_GET["erro"])) {
        $template = "error";
        $msg = urldecode($_GET["erro"]);
    } else if(isset($_GET["time"])) {
		//echo base64_decode($_GET["time"]);
        $member = hasUser(base64_decode($_GET["time"]));
        //print_r($member);
        if($token == $member->getToken()) {

        $newCobranca = $superApi->insereCobranca($member->getCid(),3);
        $cobranca_update = $member->updateCobranca($newCobranca["data"],$database);
        
        $member->deleteToken($database);
        
        $template = "cartao_confirmado";
        } else {
            $msg = "Impossível realizar esta operação no momento.";
            $template = "error";
        }
        
    }
    
}
    
require('./template/header.php');
?>


    <?php 
        if (isset($template) && $template != "") {
            include'./template/'.$template.'.php';
        }
    ?>


<?php
require('./template/footer.php');
?>