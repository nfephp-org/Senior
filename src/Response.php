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
