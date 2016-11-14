<?php

//error_reporting(-1);

// CRSF Protection
require_once('./inc/nocsrf.php');

//Database Framework Medoo
//require_once('./inc/medoo.php');

require_once('./inc/db.php');

// Classe Membro
require_once('./inc/membro.class.php');

// SCODB API Helper
require_once('./inc/scodb_api.php');

$url = "http://ceod2016.demolaysp.com.br";
//$url = "https://scodbapi-brunopansani.c9users.io";

function hasUser($cid) {
    global $database;
    
    if(!$cid)
        return false;

    $user = $database->get("inscritos", "*", [
        "cid" => $cid
        ]);

    if($user) {
        return new Membro($user,"db");
    } else {
        return false;
    }
}

function hasUserByEmail($email) {
    global $database;
    
    if(!$email)
        return false;

    $user = $database->get("inscritos", "*", [
        "email" => $email
        ]);

    if($user) {
        return new Membro($user,"db");
    } else {
        return false;
    }
}

function titleCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI"))
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
       }//foreach
       return $string;
    }
    
function formatDate($date) {
    $dt = explode("/",$date);
	return ($dt[1] . '/' . $dt[0] . '/' . $dt[2]);

}

function toConsole( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

?>