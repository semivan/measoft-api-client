<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Package extends AbstractObject
{
    /** @var string $name Название места */
    protected $name;

    /** @var string $code Внутренний код строки */
    protected $code;

    /** @var string $barcode Штрих-код места */
    protected $barcode;

    /** @var string $message Строка сообщения */
    protected $message;

    /** @var float $weight Масса места в килограммах */
    protected $weight;

    /** @var float $length Длина единицы товара */
    protected $length;

    /** @var float $width Ширина единицы товара */
    protected $width;

    /** @var float $height Высота единицы товара */
    protected $height;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'name'    => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
            'code'    => self::extractXmlValue($xml, 'code', $fromNode),
            'barcode' => self::extractXmlValue($xml, 'strbarcode', $fromNode),
            'message' => self::extractXmlValue($xml, 'message', $fromNode),
            'weight'  => self::extractXmlValue($xml, 'mass', $fromNode, 'float'),
            'length'  => self::extractXmlValue($xml, 'length', $fromNode, 'float'),
            'width'   => self::extractXmlValue($xml, 'width', $fromNode, 'float'),
            'height'  => self::extractXmlValue($xml, 'height', $fromNode, 'float'),
        ];

        return new Package($params);
    }

    /** @return string|null Название места */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null Внутренний код строки */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /** @return string|null Штрих-код места */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /** @return string|null Строка сообщения */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /** @return float|null Масса места в килограммах */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /** @return float|null Длина единицы товара */
    public function getLength(): ?float
    {
        return $this->length;
    }

    /** @return float|null Ширина единицы товара */
    public function getWidth(): ?float
    {
        return $this->width;
    }

    /** @return float|null Высота единицы товара */
    public function getHeight(): ?float
    {
        return $this->height;
    }
}