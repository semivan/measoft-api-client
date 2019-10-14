<?php

namespace Measoft\Object;

class Region extends AbstractObject
{
	/**
	 * @var string $code Код
	 */
	protected $code;

	/**
	 * @var string $name Название
	 */
	protected $name;

	/**
	 * @var Country $country Страна
	 */
	protected $country;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		if (isset($xml->country)) {
			$country = Country::getFromXml($xml->country);
		}

		$params = [
			'code'    => self::extractXmlValue($xml, 'code', $fromNode),
			'name'    => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
			'country' => $country ?? null,
		];
		
		return new Region($params);
	}

	/**
	 * @return string Код
	 */ 
	public function getCode(): ?string
	{
		return $this->code;
	}

	/**
	 * @param string $code Код
	 * @return self
	 */ 
	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return string Название
	 */ 
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name Название
	 * @return self
	 */ 
	public function setName(string $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Country Страна
	 */ 
	public function getCountry(): ?Country
	{
		return $this->country;
	}
}