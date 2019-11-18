<?php

namespace NFePHP\Senior;

use DOMDocument;
use DOMElement;
use stdClass;

class Response
{

    /**
     * Convert xml response to string with jsons
     * @param string $xml
     * @return string
     */
    public static function toJson($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $dom->loadXML($xml);
        $group = [];
        $saidas = count($dom->getElementsByTagName('saida'));
        if ($saidas == 0) {
            $erro = $dom->getElementsByTagName('erroExecucao')->item(0)->nodeValue;
            throw new Exception("Ocorreu erro! Não foram retornados dados. mensagem: [{$erro}]");
        }
        foreach ($dom->getElementsByTagName('saida') as $node) {
            $data = [];
            foreach ($node->childNodes as $child) {
                if ($child->nodeName != '#text') {
                    $value = trim($node->getElementsByTagName($child->nodeName)->item(0)->nodeValue);
                    $data[$child->nodeName] = $child->nodeValue;
                }
            }
            $group[] = json_encode($data);
        }
        return implode("\n", $group);
    }
}
