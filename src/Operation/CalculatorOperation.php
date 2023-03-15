<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\CalculationResult;
use SimpleXMLElement;

class CalculatorOperation extends AbstractOperation
{
    /** @var string $townFrom Город-отправитель */
    private $townFrom;

    /** @var string $townTo Город-получатель */
    private $townTo;

    /** @var float $length Длина в сантиметрах */
    private $length;

    /** @var float $width Ширина в сантиметрах */
    private $width;

    /** @var float $height Высота в сантиметрах */
    private $height;

    /** @var float $weight Масса в килограммах */
    private $weight;

    /** @var int $service Режим доставки */
    private $service;

    /**
     * @param string $townFrom Город-отправитель
     * @return self
     */
    public function setTownFrom(string $townFrom): self
    {
        $this->townFrom = $townFrom;

        return $this;
    }

    /**
     * @param string $townTo Город-получатель
     * @return self
     */
    public function setTownTo(string $townTo): self
    {
        $this->townTo = $townTo;

        return $this;
    }

    /**
     * @param float $length Длина в сантиметрах
     * @return self
     */
    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @param float $width Ширина в сантиметрах
     * @return self
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param float $height Высота в сантиметрах
     * @return self
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param float $weight Масса в килограммах
     * @return self
     */
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @param int $service Режим доставки
     * @return self
     */
    public function setService(int $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    protected function buildXml(): SimpleXMLElement
    {
        $xml  = $this->createXml('calculator');
        $calc = $xml->addChild('calc');

        $calc->addAttribute('townfrom', $this->townFrom);
        $calc->addAttribute('townto', $this->townTo);
        $calc->addAttribute('l', $this->length);
        $calc->addAttribute('w', $this->width);
        $calc->addAttribute('h', $this->height);
        $calc->addAttribute('mass', $this->weight);
        $calc->addAttribute('service', $this->service);

        return $xml;
    }

    /**
     * Расчет стоимости доставки
     *
     * @return CalculationResult[]
     * @throws MeasoftException
     */
    public function calculate(): array
    {
        foreach ($this->getResults() as $item) {
            $result[] = CalculationResult::getFromXml($item);
        }

        return $result ?? [];
    }
}
