<?php
require './config.php';

require './inc/superlogica_api.php';

require './inc/mail.php';

if(isset($_GET["pass"]) && $_GET["pass"] == "trueCEODsp37") {
    
    $membros = $database->query("SELECT * FROM inscritos WHERE cb_liquidada IS NULL ORDER BY id DESC")->fetchAll();
    $linhas = "";
    $falhas = "";
    $count_linha = 0;
    $count_falha = 0;
    
    $superApi = new SLAPIHelper();
    
    $cobrancas = $superApi->cobrancasByStatus("liquidadas");
    
    foreach($membros as $membro) {
        
        $member = new Membro($membro,"db");

        
        $cb_index = array_search($member->getCb_id(), array_column($cobrancas, 'id_recebimento_recb'));
        
        if(is_numeric($cb_index)) {
            
            $cobranca = $cobrancas[$cb_index];
            
            $member->updateCobranca($cobranca,$database);
            if(enviarConfirmacao($member,$mail)) {
                $linhas = $linhas.preencheLinha($member);
                $count_linha++;
            } else {
                $falhas += "Identificador de Inscri��o: ".$member->getId()."<br>";
                $count_falha++;
            }
        }
    }
    
    if($count_linha > 0 || $count_falha > 0) {
        if(enviarConfirmacaoGeral($linhas,$falhas,$mail)) {
            echo "Enviado";
        } else {
            echo "N�o deu...".$mail->ErrorInfo();
        }
    } else {
        die("Nenhuma liquida��o detectada.");
    }
} else {
    die("Voc� n�o est� autorizado a visitar essa p�gina.");
}


?>