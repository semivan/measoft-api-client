<?php

namespace Measoft\Object;

class CreateOrderPackage
{
	/**
	 * @var string $name Название места
	 */
	private $name;

	/**
	 * @var string $code Внутренний код строки
	 */
	private $code;

	/**
	 * @var string $barcode Штрих-код места
	 */
	private $barcode;

	/**
	 * @var string $message Строка сообщения
	 */
	private $message;

	/**
	 * @var float $weight Масса места в килограммах
	 */
	private $weight;

	/**
	 * @var float $length Длина единицы товара
	 */
	private $length;

	/**
	 * @var float $width Ширина единицы товара
	 */
	private $width;

	/**
	 * @var float $height Высота единицы товара
	 */
	private $height;

	/**
	 * @return string Название места
	 */ 
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name Название места
	 * @return self
	 */ 
	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string Внутренний код строки
	 */ 
	public function getCode(): ?string
	{
		return $this->code;
	}

	/**
	 * @param string $code Внутренний код строки
	 * @return self
	 */ 
	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return string Штрих-код места
	 */ 
	public function getBarcode(): ?string
	{
		return $this->barcode;
	}

	/**
	 * @param string $barcode Штрих-код места
	 * @return self
	 */ 
	public function setBarcode(string $barcode): self
	{
		$this->barcode = $barcode;

		return $this;
	}

	/**
	 * @return string Строка сообщения
	 */ 
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * @param string $message Строка сообщения
	 * @return self
	 */ 
	public function setMessage(string $message): self
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * @return float Масса места в килограммах
	 */ 
	public function getWeight(): ?float
	{
		return $this->weight;
	}

	/**
	 * @param float $weight Масса места в килограммах
	 * @return self
	 */ 
	public function setWeight(float $weight): self
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * @return float Длина единицы товара
	 */ 
	public function getLength(): ?float
	{
		return $this->length;
	}

	/**
	 * @param float $length Длина единицы товара
	 * @return self
	 */ 
	public function setLength(float $length): self
	{
		$this->length = $length;

		return $this;
	}

	/**
	 * @return float Ширина единицы товара
	 */ 
	public function getWidth(): ?float
	{
		return $this->width;
	}

	/**
	 * @param float $width Ширина единицы товара
	 * @return self
	 */ 
	public function setWidth(float $width): self
	{
		$this->width = $width;

		return $this;
	}

	/**
	 * @return float Высота единицы товара
	 */ 
	public function getHeight(): ?float
	{
		return $this->height;
	}

	/**
	 * @param float $height Высота единицы товара
	 * @return self
	 */ 
	public function setHeight(float $height): self
	{
		$this->height = $height;

		return $this;
	}
}
