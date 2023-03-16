<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Street;
use Measoft\Traits\Limitable;
use SimpleXMLElement;

class StreetSearchOperation extends AbstractOperation
{
    use Limitable;

    /** @var string $town Поиск по населенному пункту (Название или код) */
    private $town;

    /** @var string $nameContains Поиск улиц, название которых содержит указанную строку */
    private $nameContains;

    /** @var string $nameStarts Поиск улиц, название которых начинается с указанной строки */
    private $nameStarts;

    /** @var string $name Поиск улиц, название которых соответствует указанной строке */
    private $name;

    /** @var string $fullName Поиск улиц, название вместе с типом улицы которых соответствует указанной строке */
    private $fullName;

    /**
     * Поиск по населенному пункту
     *
     * @param string $town Название или код населенного пункта
     * @return self
     */
    public function town(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Поиск улиц, название которых содержит указанную строку
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
     * Поиск улиц, название которых начинается с указанной строки
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
     * Поиск улиц, название которых соответствует указанной строке
     *
     * @param string $name Строка
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Поиск улиц, название вместе с типом улицы которых соответствует указанной строке
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
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    protected function buildXml(): SimpleXMLElement
    {
        $xml        = $this->createXml('streetlist');
        $conditions = $xml->addChild('conditions');

        $conditions->addChild('town', $this->town);
        $conditions->addChild('namecontains', $this->nameContains);
        $conditions->addChild('namestarts', $this->nameStarts);
        $conditions->addChild('name', $this->name);
        $conditions->addChild('fullname', $this->fullName);

        $this->buildLimitXML($xml);

        return $xml;
    }

    /**
     * Поиск по заданным условиям
     *
     * @return Street[]
     * @throws MeasoftException
     */
    public function search(): array
    {
        if (!$this->town) {
            throw new MeasoftException('Не указан населенный пункт');
        }

        foreach ($this->getResults(false) as $item) {
            $result[] = Street::getFromXml($item);
        }

        return $result ?? [];
    }
}
