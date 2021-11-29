<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Region extends AbstractObject
{
    /** @var string $code Код */
    protected $code;

    /** @var string $name Название */
    protected $name;

    /** @var Country $country Страна */
    protected $country;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
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

    /** @return string|null Код */
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

    /** @return string|null Название */
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

    /** @return Country|null Страна */
    public function getCountry(): ?Country
    {
        return $this->country;
    }
}