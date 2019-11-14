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
    
    // use apenas os itens do filtro que lhe interessam
     
    // eabremp string  Opcional    
    //    Abrangência de Empresa. Formato Texto, mascara A[99].
    
    //eabrtcl string  Opcional
    //    Abrangência de Tipo de Colaborador. Formato Texto, mascara A[99].
    
    // eabrcad  string Opcional
    //    Abrangência do Cadastro/Matricula do Colaborador. Formato Texto, mascara A[99].
    
    // eabrcodloc string  Opcional
    //    Abrangência do Local do Colaborador. Formato Texto, mascara A[99].
    
    // eabrVin  string Opcional
    //    Abrangência do Vinculo do Colaborador. Formato Texto, mascara A[99].
    
    // edatref  string Opcional
    //    Data de Referência. Formato Texto no padrão DD/MM/YYYY.
    
    // etipsal string Obrigatório
    //    Tipo de Salário , opções : "1-Salário Integral" , "2-13º Salário" , "3-Salário Extraordinário" , "4-Auxílio Doença" e "5-Licença Maternidade" ; Formato Texto U.
    
    // eeverser string Opcional
    //    Abrangência para Eventos de Contribuição do Servidor , (opcional) Formato Texto A[99].

    $std = (object)[
        'ETipSal' => 'etipsal',
        'EDatRef' => 'edatref',
        'EEverSer' => 'eeverser',
        'EAbrEmp' => 'eabremp',
        'EAbrTcl' => 'eabrtcl',
        'EAbrCad' => 'eabrcad',
        'EAbrCodLoc' => 'eabrcodloc',
        'EAbrVin' => 'eabrvin'
    ];

    //ou passe o objeto vazio
    $std = (object)[];

    $resp = $sen->cadservidor($std);
    $txt = Response::toJson($xml); 
    //header('Content-type: text/xml; charset=UTF-8');
    echo "<pre>";
    echo $txt;
    echo "<pre>";
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
