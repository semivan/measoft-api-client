<?php

namespace Measoft\Operation;

use Measoft\Api;
use Measoft\Response;
use SimpleXMLElement;

class AbstractOperation
{
    /** @var Api $api */
    protected $api;

    /** @var Response[] $responses Массив ответов */
    private $responses = [];

    /** @param Api $api */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /** @return Response|null Поледний ответ */
    public function getLastResponse(): ?Response
    {
        return end($this->responses) ?: null;
    }

    /**
     * Создать новый экземпляр SimpleXMLElement
     *
     * @param string $startTag Корневой тег
     * @return SimpleXMLElement
     */
    protected function createXml(string $startTag): SimpleXMLElement
    {
        return simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?><'. $startTag .'/>', "SimpleXMLElement", LIBXML_NOBLANKS);
    }

    /**
     * Удалить пустые узлы из XML
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    protected function removeEmptyXmlNodes(SimpleXMLElement &$xml)
    {
        $xpath = '/child::*//*[not(*) and not(@*) and not(text()[normalize-space()])]';
        $end   = false;

        while ($end === false) {
            $end = true;

            foreach ($xml->xpath($xpath) as $node) {
                unset($node[0]);
                $end = false;
            }
        }
    }

    /**
     * Удалить пустые атрибуты из XML
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    protected function removeEmptyXmlAttributes(SimpleXMLElement &$xml)
    {
        $xpath = '//*[@*]';

        foreach ($xml->xpath($xpath) as $node) {
            $attrs = $node->attributes();

            foreach($attrs as $name => $value) {
                if (strval($value) === '') {
                    $emptyAttrNames[] = $name;
                }
            }

            foreach ($emptyAttrNames as $name) {
                unset($attrs->$name);
            }
        }
    }

    /**
     * Отправить запрос
     *
     * @param SimpleXMLElement $xml
     * @param bool             $withAuth
     * @return Response
     */
    protected function request(SimpleXMLElement $xml, bool $withAuth = true): Response
    {
        $this->removeEmptyXmlNodes($xml);
        $this->removeEmptyXmlAttributes($xml);

        //header('Content-type: text/xml'); print($xml->asXML()); exit;

        $this->responses[] = $response = $this->api->request($xml, $withAuth);;

        return $response;
    }
}