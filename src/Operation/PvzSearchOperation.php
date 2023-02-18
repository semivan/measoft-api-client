<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Pvz;
use SimpleXMLElement;

class PvzSearchOperation extends AbstractOperation
{
    /** @var string $town Поиск по городу получателя */
    private $town;

    /** @var int $code Код пункта выдачи */
    private $code;

    /** @var string $parentCode Фильтр по родительскому элементу */
    private $parentCode;

    /** @var string $acceptCash Фильтр по приему наличных */
    private $acceptCash;

    /** @var string $acceptCard Фильтр по приему банковских карт */
    private $acceptCard;

    /** @var string $acceptFitting Фильтр по наличию примерки */
    private $acceptFitting;

    /** @var string $acceptIndividuals Фильтр по доступности физическим лицам */
    private $acceptIndividuals;

    /** @var array $coordinates Поиск по координатам */
    private $coordinates;

    /**
     * Поиск по городу получателя
     *
     * @param string $town Город получателя
     * @return self
     */
    public function town(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Фильтр по коду пункта выдачи
     *
     * @param int $code
     *
     * @return $this
     */
    public function code(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Фильтр по родительскому элементу
     *
     * @param string $parentCode Родительсий элемент
     * @return self
     */
    public function parentCode(string $parentCode): self
    {
        $this->parentCode = $parentCode;

        return $this;
    }

    /**
     * Фильтр по приему наличных
     *
     * @param bool $acceptCash Признак приема наличных
     * @return self
     */
    public function acceptCash(bool $acceptCash): self
    {
        $this->acceptCash = $acceptCash ? 'YES' : 'NO';

        return $this;
    }

    /**
     * Фильтр по приему банковских карт
     *
     * @param bool $acceptCard Признак приема банковских карт
     * @return self
     */
    public function acceptCard(bool $acceptCard): self
    {
        $this->acceptCard = $acceptCard ? 'YES' : 'NO';

        return $this;
    }

    /**
     * Фильтр по наличию примерки
     *
     * @param bool $acceptFitting Признак наличия примерки
     * @return self
     */
    public function acceptFitting(bool $acceptFitting): self
    {
        $this->acceptFitting = $acceptFitting ? 'YES' : 'NO';

        return $this;
    }

    /**
     * Фильтр по доступности физическим лицам
     *
     * @param bool $acceptIndividuals Признак доступности физическим лицам
     * @return self
     */
    public function acceptIndividuals(bool $acceptIndividuals): self
    {
        $this->acceptIndividuals = $acceptIndividuals ? 'YES' : 'NO';

        return $this;
    }

    /**
     * Поиск по координатам
     *
     * @param float|null $lt Широта левого верхнего угла
     * @param float|null $lg Долгота левого верхнего угла
     * @param float|null $rt Широта правого нижнего угла
     * @param float|null $rg Долгота правого нижнего угла
     * @return self
     */
    public function coordinates(float $lt = null, float $lg = null, float $rt = null, float $rg = null): self
    {
        $this->coordinates = [
            'lt' => $lt,
            'lg' => $lg,
            'rt' => $rt,
            'rg' => $rg,
        ];

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml = $this->createXml('pvzlist');

        $xml->addChild('town', $this->town);
        $xml->addChild('code', $this->code);
        $xml->addChild('parentcode', $this->parentCode);
        $xml->addChild('acceptcash', $this->acceptCash);
        $xml->addChild('acceptcard', $this->acceptCard);
        $xml->addChild('acceptfitting', $this->acceptFitting);
        $xml->addChild('acceptindividuals', $this->acceptIndividuals);
        $xml->addChild('lt', $this->coordinates['lt'] ?? null);
        $xml->addChild('lg', $this->coordinates['lg'] ?? null);
        $xml->addChild('rt', $this->coordinates['rt'] ?? null);
        $xml->addChild('rg', $this->coordinates['rg'] ?? null);

        return $xml;
    }

    /**
     * Поиск по заданным условиям
     *
     * @return Pvz[]
     * @throws MeasoftException
     */
    public function search(): array
    {
        $response = $this->request($this->buildXml());

        if (!$response->isSuccess()) {
            throw new MeasoftException($response->getError());
        }

        $resultXml = $response->getXml();

        foreach ($resultXml as $item) {
            $result[] = Pvz::getFromXml($item);
        }

        return $result ?? [];
    }
}
