<?php

namespace Measoft\Object;

class Town extends AbstractObject
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
	 * @var string $fiasCode Код ФИАС
	 */
	protected $fiasCode;

	/**
	 * @var string $kladrCode 13-ти значный код КЛАДР
	 */
	protected $kladrCode;

	/**
	 * @var Region $region Регион
	 */
	protected $region;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		if (isset($xml->city)) {
			$region = Region::getFromXml($xml->city);
		} elseif (isset($xml['regioncode'])) {
			$region = new Region([
				'code' => strval($xml['regioncode']),
				'name' => strval($xml['regionname'] ?? ''),
			]);
		}

		$params = [
			'code'      => self::extractXmlValue($xml, 'code', $fromNode),
			'name'      => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
			'fiasCode'  => self::extractXmlValue($xml, 'fiascode', $fromNode),
			'kladrCode' => self::extractXmlValue($xml, 'kladrcode', $fromNode),
			'region'    => $region ?? null,
		];

		return new Town($params);
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
	public function setCode(string $code)
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
	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string Код ФИАС
	 */ 
	public function getFiasCode(): ?string
	{
		return $this->fiasCode;
	}

	/**
	 * @return string 13-ти значный код КЛАДР
	 */ 
	public function getKladrCode(): ?string
	{
		return $this->kladrCode;
	}

	/**
	 * @return Region Регион
	 */ 
	public function getRegion(): ?Region
	{
		return $this->region;
	}

	/**
	 * @param Region $region Регион
	 * @return self
	 */ 
	public function setRegion(Region $region): self
	{
		$this->region = $region;

		return $this;
	}
}