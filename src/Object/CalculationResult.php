<?php

namespace Measoft\Object;

class CalculationResult extends AbstractObject
{
	/**
	 * @var Town $townFrom Город-отправитель
	 */
	protected $townFrom;

	/**
	 * @var Town $townTo Город-получатель
	 */
	protected $townTo;

	/**
	 * @var float $weight Масса в килограммах
	 */
	protected $weight;

	/**
	 * @var int $service Режим доставки
	 */
	protected $service;

	/**
	 * @var string $serviceName Название режима доставки
	 */
	protected $serviceName;

	/**
	 * @var int $zone Номер тарифной зоны, по которой рассчиталась стоимость
	 */
	protected $zone;

	/**
	 * @var float $price Рассчитанная стоимость доставки в валюте прайс-листа курьерской службы
	 */
	protected $price;

	/**
	 * @var int $minDeliveryDays Минимальный срок доставки в рабочих днях
	 */
	protected $minDeliveryDays;

	/**
	 * @var int $maxDeliveryDays Максимальный срок доставки в рабочих днях
	 */
	protected $maxDeliveryDays;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
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

	/**
	 * @return Town Город-отправитель
	 */ 
	public function getTownFrom(): ?Town
	{
		return $this->townFrom;
	}

	/**
	 * @return Town Город-получатель
	 */ 
	public function getTownTo(): ?Town
	{
		return $this->townTo;
	}

	/**
	 * @return float Масса в килограммах
	 */ 
	public function getWeight(): ?float
	{
		return $this->weight;
	}

	/**
	 * @return int Режим доставки
	 */ 
	public function getService(): ?int
	{
		return $this->service;
	}

	/**
	 * @return string Название режима доставки
	 */ 
	public function getServiceName(): ?string
	{
		return $this->serviceName;
	}

	/**
	 * @return int Номер тарифной зоны, по которой рассчиталась стоимость
	 */ 
	public function getZone(): ?int
	{
		return $this->zone;
	}

	/**
	 * @return float Рассчитанная стоимость доставки в валюте прайс-листа курьерской службы
	 */ 
	public function getPrice(): ?float
	{
		return $this->price;
	}

	/**
	 * @return int Минимальный срок доставки в рабочих днях
	 */ 
	public function getMinDeliveryDays(): ?int
	{
		return $this->minDeliveryDays;
	}

	/**
	 * @return int Максимальный срок доставки в рабочих днях
	 */ 
	public function getMaxDeliveryDays(): ?int
	{
		return $this->maxDeliveryDays;
	}
}