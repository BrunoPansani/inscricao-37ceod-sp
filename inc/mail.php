<?php

require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = '';                 // SMTP username
$mail->Password = '';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to
$mail->CharSet = 'UTF-8';

$mail->isHTML(true); 
$mail->setFrom('ceod@demolaysp.com.br', 'Organização 37º CEOD');


function enviarConfirmacao($member,$mail) {
    $message = file_get_contents('./template/inscricao_confirmada.txt');
    
    $message = str_replace('%nome_inscrito%', $member->getNome(), $message);
    $message = str_replace('%cid%', $member->getCid(), $message);
    $message = str_replace('%id_cobranca%', $member->getCb_id(), $message);
    $message = str_replace('%data_liquidacao%', $member->getCb_liquidada(), $message);
    $message = str_replace('%forma_pagamento%', $member->getFormaPgtoTexto(), $message);

    $mail->AddAddress($member->getEmail());
    //$mail->AddCC('ceod@demolaysp.com.br');
    
    // Set the subject
    $mail->Subject = 'Bem-vindo! Sua inscrição no CEOD SP está confirmada =D';
    
    //Set the message
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);

    if(!$mail->Send()) {
        $mail->ClearAddresses();
        return false;
    } else {
        $mail->ClearAddresses();
        return true;
    }
        
}

function enviarConfirmacaoGeral($linhas, $falhas, $mail) {

    $message = file_get_contents('./template/inscricoes_confirmadas.txt');
    
    $message = str_replace('%linhas%', $linhas, $message);
    $message = str_replace('%data_emissao%', date("d-m-Y"), $message);
    $message = str_replace('%falhas%', $falhas, $message);

    $mail->AddAddress("ceod@demolaysp.com.br");

    // Set the subject
    $mail->Subject = '[CEOD] - '.date("d-m-Y").' - Relatório de Inscrições Confirmadas';
    
    //Set the message
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);

    if(!$mail->Send()) {
        $mail->ClearAddresses();
        return false;
    } else {
        $mail->ClearAddresses();
        return true;
    }
        
}

function preencheLinha($member) {
    $linha = file_get_contents('./template/linhas_confirmadas.txt');
    
    $linha = str_replace('%nome_inscrito%', $member->getNome(), $linha);
    $linha = str_replace('%cid_inscrito%', $member->getCid(), $linha);
    $linha = str_replace('%data_inscricao%', $member->getDataInscricao(), $linha);
    $linha = str_replace('%data_liquidada%', $member->getCb_liquidada(), $linha);
    $linha = str_replace('%forma_pagamento%', $member->getFormaPgtoTexto(), $linha);
    $linha = str_replace('%valor%', $member->getCb_valorTexto(), $linha);
    
    return $linha;
}


?>