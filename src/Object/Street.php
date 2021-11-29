<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Street extends AbstractObject
{
    /** @var string $name Полное название */
    protected $name;

    /** @var string $shortName Короткое название */
    protected $shortName;

    /** @var string $typeName Тип */
    protected $typeName;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'name'      => self::extractXmlValue($xml, 'name', $fromNode),
            'shortName' => self::extractXmlValue($xml, 'shortname', $fromNode),
            'typeName'  => self::extractXmlValue($xml, 'typename', $fromNode),
        ];

        return new Street($params);
    }

    /** @return string|null Полное название */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null Короткое название */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /** @return string|null Тип */
    public function getTypeName(): ?string
    {
        return $this->typeName;
    }
}