<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Country extends AbstractObject
{
    /** @var string $id ID */
    protected $id;

    /** @var string $code Код */
    protected $code;

    /** @var string $name Название */
    protected $name;

    /** @var string $shortName1 Короткое название */
    protected $shortName1;

    /** @var string $shortName2 Короткое название */
    protected $shortName2;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'id'         => self::extractXmlValue($xml, 'id', $fromNode),
            'code'       => self::extractXmlValue($xml, 'code', $fromNode),
            'name'       => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
            'shortName1' => self::extractXmlValue($xml, 'ShortName1', $fromNode),
            'shortName2' => self::extractXmlValue($xml, 'ShortName2', $fromNode),
        ];

        return new Country($params);
    }

    /** @return string|null ID */
    public function getId(): ?string
    {
        return $this->id;
    }

    /** @return string|null Код */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /** @return string|null Название */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null Короткое название */
    public function getShortName1(): ?string
    {
        return $this->shortName1;
    }

    /** @return string|null Короткое название */
    public function getShortName2(): ?string
    {
        return $this->shortName2;
    }
}