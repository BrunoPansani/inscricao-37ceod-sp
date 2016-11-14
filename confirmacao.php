<?php
session_start();

require('./config.php');
if (isset($_POST['cid'])) {
    try {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check('csrf_token', $_POST, true, 60 * 10, false);
    }
    catch (Exception $e) {
        header('Location: http://ceod2016.demolaysp.com.br/inscricao/');
    }
    
    $knownMember = hasUser($_POST["cid"]);
    
    if (!$knownMember) {
        $api    = new SCODBAPIHelper();
        $return = $api->memberForEvents($_POST['cid']);
        
        if ($return && get_class($return) == "Membro" && $return->isRegular()) {
            $newMember          = $return;
            $_SESSION["member"] = serialize($return);
            $template           = "new_member";
        } else if ($return && !$return->isRegular()) {
            $template = "irregular";
        } else {
            $msg      = "A CID digitada é Inválida";
            $template = "error";
        }
    } else if (isset($knownMember) && get_class($knownMember) == "Membro") {
        $_SESSION["member"] = serialize($knownMember);
        $template           = "member";
        
        if ($knownMember->getCb_id()) {
            
            if ($knownMember->getCb_liquidada()) {
                $template = "confirmada";
                $member   = $knownMember;
            } else {
                
                if (strtotime(implode("-", array_reverse(explode("/", $knownMember->getCb_data_venc())))) >= strtotime(date("Y-m-d"))) {
                    if ($knownMember->getCb_forma_pgto() == 0) {
                        $member    = $knownMember;
                        $data_venc = $knownMember->getCb_data_venc();
                        $link      = $knownMember->getCb_link_2via();
                        $template  = "final_boleto";
                    }
                }
            }
        }
    }
} else {
    $msg      = "Página inválida.";
    $template = "error";
}

// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate('csrf_token');

require('./template/header.php');
?>

        <?php
if (isset($template) && $template != "") {
    include './template/' . $template . '.php';
}
?>
            
            


<?php
require('./template/footer.php');
?>
