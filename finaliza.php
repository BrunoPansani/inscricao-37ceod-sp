<?php
session_start();

require('./config.php');

require('./inc/superlogica_api.php');
$template = "";
if (isset($_SESSION["member"]))
{
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
    }
    catch ( Exception $e )
    {
      header('Location: '.$url.'/inscricao/');   
    }
    $member = unserialize($_SESSION["member"]);
    
    $knownMember = hasUser($member->getCid());
    $superApi = new SLAPIHelper();  
    
    if(!$knownMember) {
        
        $member->setEmail($_POST["email"]);
        $member->setTelefone($_POST["celular"]);
        $member->setTamanhoCamiseta($_POST["camiseta"]);
        $member->setGrauDesejado($_POST["grau"]);
        $member->setFormaPgto($_POST["pagamento"]);
        $createdMember = $member->create($database);
        $member->setId($createdMember);
        if(!$createdMember) {
            $msg = "Não foi possível realizar a inscrição.";
            $template = "error";
        }
    } else {
        $member = $knownMember;
    }

    if(isset($_POST["pagamento"])) {
        
            $client = $superApi->clientFromCid($member->getCid());
            //print_r($client);
            if(!$client) {
            	$client = $superApi->createClient($member);
            	$msg = $client["msg"];
            }
            
            if($client) {
            	if($_POST["pagamento"] == 0) {
            	    $newCobranca = $superApi->insereCobranca($member->getCid(),0);
            	    $template = "final_boleto";
            	    
                	if($newCobranca["status"] == 200) {
                		$link = $newCobranca["data"]["link_2via"];
                		$data_venc = $newCobranca["data"]["dt_vencimento_recb"];
                		//print_r($newCobranca);
                		$cobranca_update = $member->updateCobranca($newCobranca["data"],$database);
                		if($cobranca_update == 0) {
                		    $msg = "Não foi possível inserir a cobrança. Contate ceod@demolaysp.com.br para esclarecimentos com o código abaixo: ".$newCobranca["data"]["id_recebimento_recb"];
                		    $template = "error";
                		} 
                	} else {
                		$msg = $newCobrança["msg"];
                		$template = "api_error";
                	}
            	} else if ($_POST["pagamento"] == 3) {
            	    
            	    $token = NoCSRF::generate('csrf');
        	        $bandeira = $_POST["bandeira"];
        	        $member->setToken($token);

        	        $saved_token = $member->saveToken($database);
        	        
        	        if($saved_token > 0) {
            	        $link_cartao = $superApi->urlCartao($member->getEmail(),$bandeira,urlencode($url."/inscricao/callback.php?time=".base64_encode($member->getCid())."&csrf=".$token));
            	        $template = "final_cartao";
        	        } else {
        	            $template = "error";
        	            $msg = "Houve um erro ao salvar suas opções. Tente novamente ou contate a Comissão Organizadora se isto se repetir.";
        	        }
            	}
            } else {
            	$msg = $client["msg"];
            	$template = "api_error";
            }
        }
        
        
}

// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate('csrf_token');

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
