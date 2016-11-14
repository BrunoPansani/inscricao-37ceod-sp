<?php
session_start();

$msg = "";
$error = false;
require('./config.php');

require('./inc/superlogica_api.php');
$template = "";
if (isset($_POST['csrf_token']))
{
    try
    {
        // Run CSRF check on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
    }
    catch ( Exception $e )
    {
      header('Location: http://ceod2016.demolaysp.com.br/inscricao/');   
    }
    $superApi = new SLAPIHelper();  
    $member = hasUserByEmail($_POST["email"]);
    if(!$member) {

        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $data_nascimento = $_POST["data_nascimento"];
        $celular = $_POST["celular"];
        $tipo = $_POST["tipo"];
        $camiseta = $_POST["camiseta"];
        $forma_pgto = $_POST["pagamento"];
        $bandeira = $_POST["bandeira"];
        
        if(!trim($nome)) {
            $msg += "O campo nome é obrigatório.<br>";
            $error = true;
        }
        if(!trim($email))  {
            $msg += "O campo e-mail é obrigatório.<br>";
            $error = true;
        }
        if(!trim($data_nascimento)) {
            $msg += "O campo data de nascimento é obrigatório.<br>";
            $error = true;
        } 
        
        if(!trim($celular)) {
            $msg += "O campo celular é obrigatório.<br>";
            $error = true;
        }
        if(!trim($camiseta)) {
            $msg += "O tamanho de camiseta é obrigatório.<br>";
            $error = true;
        }
        if($forma_pgto != 0 && $forma_pgto != 3) {
            $msg += "A forma de pagamento é obrigatória.<br>";
            $error = true;
        }
        if($forma_pgto == 3 && !$bandeira) {
            $msg += "A bandeira do cartão é obrigatória.<br>";
            $error = true;
        }
        if(!$error) {

            $member = new Membro();
            $member->setNome($nome);
            $member->setEmail($email);
            $member->setTelefone($celular);
            $member->setDataNascimento($data_nascimento,true);
            $member->setTamanhoCamiseta($camiseta);
            $member->setFormaPgto($forma_pgto);
            
            $id = $member->create($database); // Membro criado, se Deus quiser
            
            $member = hasUserByEmail($email);
        }
        
    }
            
    if($member && !$error) {
        if(isset($_POST["pagamento"])) {
            
            $client = $superApi->clientFromCid($member->getCid());

            if(!$client) {
            	$client = $superApi->createClient($member);
            	$msg = $client["msg"];
            }
            if($client) {
            	if($_POST["pagamento"] == 0) {
            	    $template = "final_boleto";

            	    if(!$member->getCb_id()) {
            	        $newCobranca = $superApi->insereCobranca($member->getCid(),0);
            	    
                    	if($newCobranca["status"] == 200) {
                    		$link = $newCobranca["data"]["link_2via"];
                    		$data_venc = $newCobranca["data"]["dt_vencimento_recb"];
                    		
                    		$cobranca_update = $member->updateCobranca($newCobranca["data"],$database);
                    		print_r($cobranca_update);
                    		if($cobranca_update == 0) {
                    		    $msg = "Não foi possível inserir a cobrança. Contate ceod@demolaysp.com.br para esclarecimentos com o código abaixo: ".$newCobranca["data"]["id_recebimento_recb"];
                    		    $template = "error";
                    		} 
                    	} else {
                    		$msg = $newCobrança["msg"];
                    		print_r($newCobranca);
                    		$template = "api_error";
                    	}
            	    } else if ($member->getCb_forma_pgto == 0) {
            	        if($member->getCb_liquidada() && $member->getCb_liquidada() != "00/00/0000") {
        	                $template = "confirmada";
        	                echo $member->getCb_liquidada();
        	            } else {
        	                $link = $member->getCb_link_2via();

        	                $template = "final_boleto";
        	            }
            	    }
            	} else if ($_POST["pagamento"] == 3) {
                    if($member->getCb_liquidada() && $member->getCb_liquidada() != "00/00/0000") {
        	            $template = "confirmada";
        	        } else {
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
            	}
            } else {
            	$msg = $client["msg"];
            	$template = "api_error";
            }
        }

    
    } else {
        $msg += "Houve um erro ao criar sua inscrição. Tente novamente.";
        $error = true;
        $template = "error";
        }
     
}
else
{
    $msg = "Página inválida.";
    $template = "error";
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
