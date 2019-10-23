<?php

namespace NFePHP\Senior;

class Senior
{

    protected $xmlns = "http://services.senior.com.br";
    protected $urls = [
        "joinville" => "https://seniorws.joinville.sc.gov.br/g5-senior-services/rubi_Syncintegracaoactuary"
    ];

    private $city;
    private $user;
    private $password;
    private $encryption;
    private $url;

    public function __construct($user, $password, $city, $encryption)
    {
        $this->city = strtolower($city);
        $this->user = $user;
        $this->password = $password;
        $this->encryption = $encryption;
        if (!array_key_exists($this->city, $this->urls)) {
            throw new Exception('Essa cidade não foi inclusa no projeto ainda.');
        }
        $this->url = $this->urls[$this->city];
    }

    /**
     * Dados de afastamento
     *
     * @param string $eabremp       Opcional    Abrangência de Empresa. Formato Texto, mascara A[99].
     * @param string $eabrtcl       Opcional	Abrangência de Tipo de Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrcad       Opcional	Abrangência do Cadastro/Matricula do Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrcodloc    Opcional	Abrangência do Local do Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrVin	    Opcional	Abrangência do Vinculo do Colaborador. Formato Texto, mascara A[99].
     * @return void
     */
    public function afastamento(\stdClass $std)
    {
        $action = 'afastamento';
        $possible = [
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }

    /**
     * 
     * @param string $edatref       Opcional	Data de Referência. Formato Texto no padrão DD/MM/YYYY.
     * @param string $eabremp       Opcional	Abrangência de Empresa. Formato Texto, mascara A[99].
     * @param string $eabrtcl       Opcional	Abrangência de Tipo de Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrcad       Opcional	Abrangência do Cadastro/Matricula do Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrcodloc    Opcional	Abrangência do Local do Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrVin       Opcional	Abrangência do Vinculo do Colaborador. Formato Texto, mascara A[99].
     */
    public function caddep($possible, \stdClass $std)
    {
        $action = 'caddep';
        $possible = [
            'EDatRef',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin'
        ];
        $parameters = $this->buildParams($std);
        return $this->send($action, $parameters);

    }

    public function cadservidor($possible, \stdClass $std)
    {
        $action = 'cadservidor';
        $possible = [
            'EDatRef',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin'
        ];
        $parameters = $this->buildParams($std);
        return $this->send($action, $parameters);

    }
    
    /**
     * 
     * @param string $etipsal       Obrigatório Tipo de Salário , opções : "1-Salário Integral" , "2-13º Salário" , "3-Salário Extraordinário" , "4-Auxílio Doença" e "5-Licença Maternidade" ; Formato Texto U.
     * @param string $edatref       Opcional    Data de Referência. Formato Texto no padrão DD/YYYY.
     * @param string $eeverser      Opcional    Abrangência para Eventos de Contribuição do Servidor , (opcional) Formato Texto A[99].
     * @param string $eabremp       Opcional    Abrangência de Empresa. Formato Texto, mascara A[99].
     * @param string $eabrtcl       Opcional    Abrangência de Tipo de Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrcad       Opcional    Abrangência do Cadastro/Matricula do Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrcodloc    Opcional    Abrangência do Local do Colaborador. Formato Texto, mascara A[99].
     * @param string $eabrVin       Opcional    Abrangência do Vinculo do Colaborador. Formato Texto, mascara A[99].
     */
    public function salcontrib(\stdClass $std)
    {
        $action = 'salcontrib';
        $possible = [
            'ETipSal',
            'EDatRef',
            'EEverSer',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }
    
    /**
     * Constroi a tag entradas
     * 
     * @param array $possible
     * @param \stdClass $std
     * @return string
     */
    protected function buildParams(array $possible, \stdClass $std)
    {
        $arr = array_change_key_case(get_object_vars($std), CASE_LOWER);
        $std = json_decode(json_encode($arr));
       
        $filter = '';
        foreach ($possible as $key) {
            $keylower = strtolower($key);
            if (!empty($std->$keylower)) {
                $filter .= "<$key>". $std->$keylower . "</$key>";
            }
        }
        $parameters = '';
        if (!empty($filter)) {
            $parameters = '<entrada>'
                . $filter
                . "</entrada>";
        }
        return $parameters;   
    }
    
    /**
     * Envia a requisição
     * @param string $action
     * @param string $parameters
     * @return string
     * @throws \RuntimeException
     */
    protected function send($action, $parameters)
    {
        $message = "<ser:$action>"
            . "<user>$this->user</user>"
            . "<password>$this->password</password>"
            . "<encryption>$this->encryption</encryption>"
            . "<parameters>$parameters</parameters>"
            . "</ser:$action>";
        
        $envelope = "<soapenv:Envelope "
            . "xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" "
            . "xmlns:ser=\"$this->xmlns\">"
            . "<soapenv:Header/>"
            . "<soapenv:Body>";
        $envelope .= $message . "</soapenv:Body></soapenv:Envelope>";

        $contentLength = strlen($envelope);
        $headers = [
            'User-Agent: PHP-SOAP',
            'Accept-Encoding: gzip,deflate',
            'Content-Type: text/xml;charset=UTF-8',
            "SOAPAction: \"$action\"",
            "Content-Length: $contentLength",
            'Expect: 100-continue',
            'Connection: Keep-Alive'
        ];
        die;
        $txtheaders = implode("\n", $headers);
        
        try {
            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_URL, $this->url);
            curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soaptimeout);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, $this->soaptimeout + 20);
            //dont use security check
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($oCurl, CURLOPT_FAILONERROR, 0);
            curl_setopt($oCurl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($oCurl, CURLOPT_USERPWD, $this->user . ":" . $this->password);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $envelope);
            curl_setopt($oCurl, CURLOPT_HEADER, 1);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
            curl_setopt($oCurl, CURLOPT_CERTINFO, 1);

            $response = curl_exec($oCurl);

            $this->soaperror = curl_error($oCurl);
            $this->soapinfo = curl_getinfo($oCurl);

            $headsize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
            $httpcode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
            $bodysize = curl_getinfo($oCurl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            $bodyType = curl_getinfo($oCurl, CURLINFO_CONTENT_TYPE);

            curl_close($oCurl);

            $responseHead = substr($response, 0, $headsize);
            $responseBody = substr($response, $headsize);
            if (substr($response, -3) !== substr($responseBody, -3)) {
                throw new \RuntimeException(
                    'A terminação dos dados compactados está diferente! Não foi possivel extrair os dados.'
                );
            }
            $marker = substr($responseBody, 0, 3);
            //identify compress body in gzip deflate
            if ($marker === chr(0x1F) . chr(0x8B) . chr(0x08)) {
                $responseBody = gzdecode($responseBody);
            }
            $this->saveDebugFiles(
                $action,
                $txtheaders . "\n" . $envelope,
                $responseHead . "\n" . $responseBody,
                $this->debugmode
            );
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        if ($this->soaperror != '') {
            throw new \RuntimeException($this->soaperror);
        }
        if ($httpcode != 200) {
            throw new \RuntimeException($responseHead . '\n' . $responseBody);
        }
        return $responseBody;
    }

}
