<?php

namespace Measoft\Object;

class Street extends AbstractObject
{
	/**
	 * @var string $name Полное название
	 */
	protected $name;

	/**
	 * @var string $shortName Короткое название
	 */
	protected $shortName;

	/**
	 * @var string $typeName Тип
	 */
	protected $typeName;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		$params = [
			'name'      => self::extractXmlValue($xml, 'name', $fromNode),
			'shortName' => self::extractXmlValue($xml, 'shortname', $fromNode),
			'typeName'  => self::extractXmlValue($xml, 'typename', $fromNode),
		];

		return new Street($params);
	}

	/**
	 * @return string Полное название
	 */ 
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return string Короткое название
	 */ 
	public function getShortName(): ? string
	{
		return $this->shortName;
	}

	/**
	 * @return string Тип
	 */ 
	public function getTypeName()
	{
		return $this->typeName;
	}
}