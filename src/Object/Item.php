<?php

namespace Measoft\Object;

class Item extends AbstractObject
{
	/**
	 * @var string $code Код номенклатуры
	 */
	protected $code;

	/**
	 * @var string $article Артикул
	 */
	protected $article;

	/**
	 * @var string $barcode Штрих-код производителя
	 */
	protected $barcode;

	/**
	 * @var string $name Наименование
	 */
	protected $name;

	/**
	 * @var float $retailPrice Розничная цена по-умолчанию. При оформлении заказа цена используется та, которая указана в заказе.
	 */
	protected $retailPrice;

	/**
	 * @var float $purchasePrice Закупочная цена
	 */
	protected $purchasePrice;

	/**
	 * @var float $weight Масса в килограммах
	 */
	protected $weight;

	/**
	 * @var float $length Длина в сантиметрах
	 */
	protected $length;

	/**
	 * @var float $width Ширина в сантиметрах
	 */
	protected $width;

	/**
	 * @var float $height Высота в сантиметрах
	 */
	protected $height;

	/**
	 * @var int $countInPallet Количество штук в паллете
	 */
	protected $countInPallet;

	/**
	 * @var bool $hasSerials Требует ли учета серийных номеров
	 */
	protected $hasSerials;

	/**
	 * @var string $countryOfOrigin Наименование страны происхождения на русском языке
	 */
	protected $countryOfOrigin;

	/**
	 * @var string $comment Комментарий
	 */
	protected $comment;

	/**
	 * @var string $comment2 Дополнительный комментарий
	 */
	protected $comment2;

	/**
	 * @var int $quantity Количество на складе
	 */
	protected $quantity;

	/**
	 * @var int $reservedQuantity Количество зарезервированного товара
	 */
	protected $reservedQuantity;

	/**
	 * @var string $externalCode Внешний код строки
	 */
	protected $externalCode;

	/**
	 * @var int $vatRate Ставка НДС
	 */
	protected $vatRate;

	/**
	 * @var int $returns Количество данного товара, от которого отказался получатель
	 */
	protected $returns;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		$params = [
			'name'             => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
			'code'             => self::extractXmlValue($xml, 'code', $fromNode),
			'article'          => self::extractXmlValue($xml, 'article', $fromNode),
			'barcode'          => self::extractXmlValue($xml, 'barcode', $fromNode),
			'retailPrice'      => self::extractXmlValue($xml, 'retprice', $fromNode, 'float'),
			'purchasePrice'    => self::extractXmlValue($xml, 'purchprice', $fromNode, 'float'),
			'weight'           => self::extractXmlValue($xml, ['node' => 'weight', 'attr' => 'mass'], $fromNode, 'float'),
			'length'           => self::extractXmlValue($xml, 'length', $fromNode, 'float'),
			'width'            => self::extractXmlValue($xml, 'width', $fromNode, 'float'),
			'height'           => self::extractXmlValue($xml, 'height', $fromNode, 'float'),
			'countInPallet'    => self::extractXmlValue($xml, 'CountInPallet', $fromNode, 'int'),
			'hasSerials'       => self::extractXmlValue($xml, 'HasSerials', $fromNode, 'bool'),
			'countryOfOrigin'  => self::extractXmlValue($xml, 'CountryOfOrigin', $fromNode),
			'comment'          => self::extractXmlValue($xml, 'Message', $fromNode),
			'comment2'         => self::extractXmlValue($xml, 'Message2', $fromNode),
			'quantity'         => self::extractXmlValue($xml, 'quantity', $fromNode, 'int'),
			'reservedQuantity' => self::extractXmlValue($xml, 'reserved', $fromNode, 'int'),
			'externalCode'     => self::extractXmlValue($xml, 'extcode', $fromNode),
			'vatRate'          => self::extractXmlValue($xml, 'VATrate', $fromNode, 'int'),
			'returns'          => self::extractXmlValue($xml, 'returns', $fromNode, 'int'),
		];

		return new Item($params);
	}

	/**
	 * @return string Код номенклатуры
	 */ 
	public function getCode(): ?string
	{
		return $this->code;
	}

	/**
	 * @return string Артикул
	 */ 
	public function getArticle(): ?string
	{
		return $this->article;
	}

	/**
	 * @return string Штрих-код производителя
	 */ 
	public function getBarcode(): ?string
	{
		return $this->barcode;
	}

	/**
	 * @return string Наименование
	 */ 
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return float Розничная цена по-умолчанию
	 */ 
	public function getRetailPrice(): ?float
	{
		return $this->retailPrice;
	}

	/**
	 * @return float Закупочная цена
	 */ 
	public function getPurchasePrice(): ?float
	{
		return $this->purchasePrice;
	}

	/**
	 * @return float Масса в килограммах
	 */ 
	public function getWeight(): ?float
	{
		return $this->weight;
	}

	/**
	 * @return float Длина в сантиметрах
	 */ 
	public function getLength(): ?float
	{
		return $this->length;
	}

	/**
	 * @return float Ширина в сантиметрах
	 */ 
	public function getWidth(): ?float
	{
		return $this->width;
	}

	/**
	 * @return float Высота в сантиметрах
	 */ 
	public function getHeight(): ?float
	{
		return $this->height;
	}

	/**
	 * @return int Количество штук в паллете
	 */ 
	public function getCountInPallet(): ?int
	{
		return $this->countInPallet;
	}

	/**
	 * @return bool Требует ли учета серийных номеров
	 */ 
	public function getHasSerials(): ?bool
	{
		return $this->hasSerials;
	}

	/**
	 * @return string Наименование страны происхождения на русском языке
	 */ 
	public function getCountryOfOrigin(): ?string
	{
		return $this->countryOfOrigin;
	}

	/**
	 * @return string Комментарий
	 */ 
	public function getComment(): ?string
	{
		return $this->comment;
	}

	/**
	 * @return string Дополнительный комментарий
	 */ 
	public function getComment2(): ?string
	{
		return $this->comment2;
	}

	/**
	 * @return int Количество на складе
	 */ 
	public function getQuantity(): ?int
	{
		return $this->quantity;
	}

	/**
	 * @return int Количество зарезервированного товара
	 */ 
	public function getReservedQuantity(): ?int
	{
		return $this->reservedQuantity;
	}

	/**
	 * @return string Внешний код строки
	 */ 
	public function getExternalCode(): ?string
	{
		return $this->externalCode;
	}

	/**
	 * @return int Ставка НДС
	 */ 
	public function getVatRate(): ?int
	{
		return $this->vatRate;
	}

	/**
	 * @return int Количество данного товара, от которого отказался получатель
	 */ 
	public function getReturns(): ?int
	{
		return $this->returns;
	}
}