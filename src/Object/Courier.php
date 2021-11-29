<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Courier extends AbstractObject
{
    /** @var string $code Код */
    protected $code;

    /** @var string $name Имя */
    protected $name;

    /** @var string $phone Телефон */
    protected $phone;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'code'  => self::extractXmlValue($xml, 'code', $fromNode),
            'name'  => self::extractXmlValue($xml, 'name', $fromNode),
            'phone' => self::extractXmlValue($xml, 'phone', $fromNode),
        ];

        return new Courier($params);
    }

    /** @return string|null Код */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /** @return string|null Имя */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null Телефон */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
}