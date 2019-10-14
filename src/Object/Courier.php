<?php

namespace Measoft\Object;

class Courier extends AbstractObject
{
	/**
	 * @var string $code Код
	 */
	protected $code;

	/**
	 * @var string $name Имя
	 */
	protected $name;

	/**
	 * @var string $phone Телефон
	 */
	protected $phone;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		$params = [
			'code'  => self::extractXmlValue($xml, 'code', $fromNode),
			'name'  => self::extractXmlValue($xml, 'name', $fromNode),
			'phone' => self::extractXmlValue($xml, 'phone', $fromNode),
		];

		return new Courier($params);
	}

	/**
	 * @return string Код
	 */ 
	public function getCode(): ?string
	{
		return $this->code;
	}

	/**
	 * @return string Имя
	 */ 
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return string Телефон
	 */ 
	public function getPhone(): ?string
	{
		return $this->phone;
	}
}