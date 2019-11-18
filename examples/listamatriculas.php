<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Senior\Senior;

try {
    $user = '1234567';
    $password = 'aquisuasenha';
    $cidade = 'joinville';
    $encriptacao = 0;

    $sen = new Senior($user, $password, $cidade, $encriptacao);
    //em desenvolvimento pode gravar as mensagens para debug
    $sen->setDebugMode(true);
    
    $std = (object)[
        'EDatRef' => '10/2019', //OPCIONAL MM/YYYY ou DD/MM/YYYY
        'EAbrEmp' => '2', //OBRIGATORIO ex. 2 - IPREVILLE 
        'EAbrTcl' => '1',//OPCIONAL ex. 1 - Empregado 
        'EAbrCad' => null, //OPCIONAL
        'EAbrCod' => null, //OPCIONAL
        'EAbrVin' => '5',//OPCIONAL  5 - Estatutário Efetivo 
        'numeroPagina' => '1',
        'registrosPorPagina' => '50'
    ];

    $resp = $sen->listaMatriculas($std);
    
    //para ver o retorno em xml use as duas linhas abaixo
    header('Content-type: text/xml; charset=UTF-8');
    echo $resp;
    
    //para converter em txt de jsons use as linhas abaixo
    //e comente as duas linhas anteriores
    //$txt = Response::toJson($resp); 
    //echo "<pre>";
    //echo $txt;
    //echo "</pre>";
    
    
} catch (\Exception $e) {
    echo $e->getMessage();
}