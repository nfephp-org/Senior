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
        'EDatRef' => '10/2019',
        'ETipSal' => '1', //ex. 1-Salário Integral
        'EEverSer' => null,
        'EAbrEmp' => '2', //ex. 2 - IPREVILLE 
        'EAbrTcl' => null,
        'EAbrCad' => null,
        'EAbrCodLoc' => null,
        'EAbrVin' => '5',
        'numeroPagina' => 1,
        'registrosPorPagina' => 50
    ];

    $resp = $sen->salcontrib($std);

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
