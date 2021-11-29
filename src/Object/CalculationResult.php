<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class CalculationResult extends AbstractObject
{
    /** @var Town $townFrom Город-отправитель */
    protected $townFrom;

    /** @var Town $townTo Город-получатель */
    protected $townTo;

    /** @var float $weight Масса в килограммах */
    protected $weight;

    /** @var int $service Режим доставки */
    protected $service;

    /** @var string $serviceName Название режима доставки */
    protected $serviceName;

    /** @var int $zone Номер тарифной зоны, по которой рассчиталась стоимость */
    protected $zone;

    /** @var float $price Рассчитанная стоимость доставки в валюте прайс-листа курьерской службы */
    protected $price;

    /** @var int $minDeliveryDays Минимальный срок доставки в рабочих днях */
    protected $minDeliveryDays;

    /** @var int $maxDeliveryDays Максимальный срок доставки в рабочих днях */
    protected $maxDeliveryDays;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        if (isset($xml->townfrom)) {
            $townFrom = Town::getFromXml($xml->townfrom, false);
        }

        if (isset($xml->townfrom)) {
            $townTo = Town::getFromXml($xml->townto, false);
        }

        $params = [
            'townFrom'        => $townFrom ?? null,
            'townTo'          => $townTo   ?? null,
            'weight'          => self::extractXmlValue($xml, 'mass', $fromNode, 'float'),
            'service'         => self::extractXmlValue($xml, 'service', $fromNode, 'int'),
            'serviceName'     => self::extractXmlValue($xml->service ?? null, 'name', false),
            'zone'            => self::extractXmlValue($xml, 'zone', $fromNode, 'int'),
            'price'           => self::extractXmlValue($xml, 'price', $fromNode, 'float'),
            'minDeliveryDays' => self::extractXmlValue($xml, 'mindeliverydays', $fromNode, 'int'),
            'maxDeliveryDays' => self::extractXmlValue($xml, 'maxdeliverydays', $fromNode, 'int'),
        ];

        return new CalculationResult($params);
    }

    /** @return Town|null Город-отправитель */
    public function getTownFrom(): ?Town
    {
        return $this->townFrom;
    }

    /** @return Town|null Город-получатель */
    public function getTownTo(): ?Town
    {
        return $this->townTo;
    }

    /** @return float|null Масса в килограммах */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /** @return int|null Режим доставки */
    public function getService(): ?int
    {
        return $this->service;
    }

    /** @return string|null Название режима доставки */
    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    /** @return int|null Номер тарифной зоны, по которой рассчиталась стоимость */
    public function getZone(): ?int
    {
        return $this->zone;
    }

    /** @return float|null Рассчитанная стоимость доставки в валюте прайс-листа курьерской службы */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /** @return int|null Минимальный срок доставки в рабочих днях */
    public function getMinDeliveryDays(): ?int
    {
        return $this->minDeliveryDays;
    }

    /** @return int|null Максимальный срок доставки в рабочих днях */
    public function getMaxDeliveryDays(): ?int
    {
        return $this->maxDeliveryDays;
    }
}