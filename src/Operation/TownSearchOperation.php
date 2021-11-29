<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Town;
use SimpleXMLElement;

class TownSearchOperation extends AbstractOperation
{
    /** @var string $zipCode Поиск по индексу */
    private $zipCode;

    /** @var string $kladrCode Поиск по 13-ти значному коду КЛАДР */
    private $kladrCode;

    /** @var string $fiasCode Поиск по коду ФИАС */
    private $fiasCode;

    /** @var string $code Поиск по коду в системе */
    private $code;

    /** @var string $regionName Поиск по всем населенным пунктам региона */
    private $regionName;

    /** @var string $nameContains Поиск населенных пунктов, название которых содержит указанную строку */
    private $nameContains;

    /** @var string $nameStarts Поиск населенных пунктов, название которых начинается с указанной строки */
    private $nameStarts;

    /** @var string $name Поиск населенных пунктов, название которых соответствует указанной строке */
    private $name;

    /** @var string $fullName Поиск населенных пунктов, название вместе с типом населенного пункта которых соответствует указанной строке */
    private $fullName;

    /** @var string $countryCode Поиск только по стране с указанным кодом */
    private $countryCode;

    /** @var int $offset Задает номер записи результата, начиная с которой выдавать ответ */
    private $offset = 0;

    /** @var int $limit Задает количество записей результата, которые нужно вернуть */
    private $limit = 10000;

    /** @var string $countAll YES указывает на необходимость подсчета общего количества найденных совпадений */
    private $countAll;

    /**
     * Поиск по индексу
     *
     * @param string $zipCode Индекс
     * @return self
     */
    public function zipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Поиск по 13-ти значному коду КЛАДР
     *
     * @param string $kladrCode 13-ти значный код КЛАДР
     * @return self
     */
    public function kladrCode(string $kladrCode): self
    {
        $this->kladrCode = $kladrCode;

        return $this;
    }

    /**
     * Поиск по коду ФИАС
     *
     * @param string $fiasCode Код ФИАС
     * @return self
     */
    public function fiasCode(string $fiasCode): self
    {
        $this->fiasCode = $fiasCode;

        return $this;
    }

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
     * Поиск по всем населенным пунктам региона
     *
     * @param string $regionName Название региона
     * @return self
     */
    public function regionName(string $regionName): self
    {
        $this->regionName = $regionName;

        return $this;
    }

    /**
     * Поиск населенных пунктов, название которых содержит указанную строку
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
     * Поиск населенных пунктов, название которых начинается с указанной строки
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
     * Поиск населенных пунктов, название которых соответствует указанной строке
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
     * Поиск населенных пунктов, название вместе с типом населенного пункта которых соответствует указанной строке
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
     * Задать номер записи результата, начиная с которой выдавать ответ
     *
     * @param int $offset Номер записи
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Задаеть количество записей результата, которые нужно вернуть
     *
     * @param int $limit Количество записей результата
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Установить подсчет общего количества найденных совпадений
     *
     * @param bool $countAll Вести подсчет общего количества найденных совпадений
     * @return self
     */
    public function countAll(bool $countAll = true): self
    {
        $this->countAll = $countAll === true ? 'YES' : null;

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml        = $this->createXml('townlist');
        $codesearch = $xml->addChild('codesearch');
        $conditions = $xml->addChild('conditions');
        $limit      = $xml->addChild('limit');

        $codesearch->addChild('zipcode', $this->zipCode);
        $codesearch->addChild('kladrcode', $this->kladrCode);
        $codesearch->addChild('fiascode', $this->fiasCode);
        $codesearch->addChild('code', $this->code);

        $conditions->addChild('region', $this->regionName);
        $conditions->addChild('namecontains', $this->nameContains);
        $conditions->addChild('namestarts', $this->nameStarts);
        $conditions->addChild('name', $this->name);
        $conditions->addChild('fullname', $this->fullName);
        $conditions->addChild('country', $this->countryCode);

        $limit->addChild('limitfrom', $this->offset);
        $limit->addChild('limitcount', $this->limit);
        $limit->addChild('countall', $this->countAll);

        return $xml;
    }

    /**
     * Поиск по заданным условиям
     *
     * @return Town[]
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
            $result[] = Town::getFromXml($item);
        }

        return $result ?? [];
    }
}