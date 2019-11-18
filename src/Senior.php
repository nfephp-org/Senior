<?php

namespace NFePHP\Senior;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Senior
{

    protected $xmlns = "http://services.senior.com.br";
    protected $urls = [
        "joinville" => "https://seniorws.joinville.sc.gov.br/g5-senior-services/rubi_Syncintegracaoactuary"
    ];
    private $soaptimeout = 10;
    private $tpAmb = 1;
    private $debugmode = false;
    private $city;
    private $user;
    private $password;
    private $encryption;
    private $url;
    private $logger;

    public function __construct($user, $password, $city, $encryption)
    {
        $this->logger = new Logger('senior');
        $this->logger->pushHandler(
            new StreamHandler(__DIR__ . '/../storage/log/app.log', Logger::DEBUG)
        );
        
        $this->city = strtolower($city);
        $this->user = $user;
        $this->password = $password;
        $this->encryption = $encryption;
        if (!array_key_exists($this->city, $this->urls)) {
            $msg = 'Essa cidade não foi inclusa no projeto ainda.';
            $this->logger->error($msg);
            throw new \Exception($msg);
        }
        $this->url = $this->urls[$this->city];
    }
    
    /**
     * Set environment 2 to tests and 1 to production
     * @param int $tpAmb
     * @return int
     */
    public function setAmbiente($tpAmb = null)
    {
        if (!empty($tpAmb) && ($tpAmb == 1 || $tpAmb == 2)) {
            $this->tpAmb = $tpAmb;
        }
        return $this->tpAmb;
    }
    
    /**
     * Set debugmod
     * When debugmod = true, files with soap content will be saved for analises
     * @param bool $flag
     * @return void
     */
    public function setDebugMode($flag = true)
    {
        if (!empty($flag)) {
            $this->debugmode = $flag;
        }
        return $this->debugmode;
    }
    
    /**
     * Set soap timeout parameter in seconds
     * @param int $sec
     * @return void
     */
    public function setSoapTimeOut($sec = 10)
    {
        if (!empty($sec)) {
            $this->soaptimeout = $sec;
        }
        return $this->soaptimeout;
    }
    
    /**
     *
     * @param \stdClass $std
     * @return string
     */
    public function listaMatriculas(\stdClass $std)
    {
        $action = 'listaMatriculas';
        $possible = [
            'EDatRef',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCod',
            'EAbrVin',
            'numeroPagina',
            'registrosPorPagina'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }

    /**
     * Dados de afastamento
     *
     * @param \stdClass $std
     * @return string
     */
    public function afastamento(\stdClass $std)
    {
        $action = 'afastamento';
        $possible = [
            'EDatRef',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin',
            'numeroPagina',
            'registrosPorPagina'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }

    /**
     * Dados de dependentes
     *
     * @param \stdClass $std
     * @return string
     */
    public function caddep(\stdClass $std)
    {
        $action = 'caddep';
        $possible = [
            'EDatRef',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin',
            'numeroPagina',
            'registrosPorPagina'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }

    /**
     * Dados de servidores
     *
     * @param \stdClass $std
     * @return string
     */
    public function cadservidor(\stdClass $std)
    {
        $action = 'cadservidor';
        $possible = [
            'EDatRef',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin',
            'numeroPagina',
            'registrosPorPagina'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }
    
    /**
     * Dados de salario contribuição
     *
     * @param \stdClass $std
     * @return string
     */
    public function salcontrib(\stdClass $std)
    {
        $action = 'salcontrib';
        $possible = [
            'EDatRef',
            'ETipSal',
            'EEverSer',
            'EAbrEmp',
            'EAbrTcl',
            'EAbrCad',
            'EAbrCodLoc',
            'EAbrVin',
            'numeroPagina',
            'registrosPorPagina'
        ];
        $parameters = $this->buildParams($possible, $std);
        return $this->send($action, $parameters);
    }
    
    /**
     * Construtor da tag entrada
     *
     * @param array $possible campos possiveis
     * @param \stdClass $std dados
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
            $responseBody = str_replace("'", '"', $responseBody);
            $this->saveDebugFiles(
                $action,
                $txtheaders . "\n" . $envelope,
                $responseHead . "\n" . $responseBody,
                $this->debugmode
            );
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $this->logger->error($msg);
            throw new \RuntimeException($msg);
        }
        if ($this->soaperror != '') {
            $msg = $this->soaperror;
            $this->logger->error($msg);
            throw new \RuntimeException($msg);
        }
        if ($httpcode != 200) {
            $msg = $responseHead . '\n' . $responseBody;
            $this->logger->error($msg);
            throw new \RuntimeException($msg);
        }
        return $responseBody;
    }
    
    /**
     * Save request envelope and response for debug reasons
     *
     * @param string $action
     * @param string $request
     * @param string $response
     * @return void
     */
    protected function saveDebugFiles($action, $request, $response, $flag = false)
    {
        if (!$flag) {
            return;
        }
        $tempdir = realpath(__DIR__ . "/../storage/debug");
        $num = date('mdHis');
        file_put_contents($tempdir . "/req_" . $action . "_" . $num . ".txt", $request);
        file_put_contents($tempdir . "/res_" . $action . "_" . $num . ".txt", $response);
    }
}
