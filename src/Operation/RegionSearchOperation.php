<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Region;
use SimpleXMLElement;

class RegionSearchOperation extends AbstractOperation
{
    /** @var string $code Поиск по коду в системе */
    private $code;

    /** @var string $nameContains Поиск регионов, название которых содержит указанную строку */
    private $nameContains;

    /** @var string $nameStarts Поиск регионов, название которых начинается с указанной строки */
    private $nameStarts;

    /** @var string $fullName Поиск регионов, название которых соответствует указанной строке */
    private $fullName;

    /** @var string $countryCode Поиск только по стране с указанным кодом */
    private $countryCode;

    /**
     * Поиск по коду в системе
     *
     * @param string $code Код в системе
     * @return self
     */
    public function code(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Поиск регионов, название которых содержит указанную строку
     *
     * @param string $nameContains Строка
     * @return self
     */
    public function nameContains(string $nameContains): self
    {
        $this->nameContains = $nameContains;

        return $this;
    }

    /**
     * Поиск регионов, название которых начинается с указанной строки
     *
     * @param string $nameStarts Строка
     * @return self
     */
    public function nameStarts(string $nameStarts): self
    {
        $this->nameStarts = $nameStarts;

        return $this;
    }

    /**
     * Поиск регионов, название которых соответствует указанной строке
     *
     * @param string $fullName Строка
     * @return self
     */
    public function fullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Поиск только по стране с указанным кодом
     *
     * @param string $countryCode Код страны
     * @return self
     */
    public function countryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml        = $this->createXml('regionlist');
        $codesearch = $xml->addChild('codesearch');
        $conditions = $xml->addChild('conditions');

        $codesearch->addChild('code', $this->code);

        $conditions->addChild('namecontains', $this->nameContains);
        $conditions->addChild('namestarts', $this->nameStarts);
        $conditions->addChild('fullname', $this->fullName);
        $conditions->addChild('country', $this->countryCode);

        return $xml;
    }

    /**
     * Поиск по заданным условиям
     *
     * @return Region[]
     * @throws MeasoftException
     */
    public function search(): array
    {
        $response = $this->request($this->buildXml(), false);

        if (!$response->isSuccess()) {
            throw new MeasoftException($response->getError());
        }

        $resultXml = $response->getXml();

        foreach ($resultXml as $item) {
            $result[] = Region::getFromXml($item);
        }

        return $result ?? [];
    }
}