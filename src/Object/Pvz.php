<?php

namespace Measoft\Object;

class Pvz extends AbstractObject
{
	/**
	 * @var string $code Код ПВЗ в системе
	 */
	protected $code;

	/**
	 * @var string $clientCode Код ПВЗ используемый компанией-подрядчиком 
	 */
	protected $clientCode;

	/**
	 * @var string $name Наименование
	 */
	protected $name;

	/**
	 * @var string $parentCode Код родительского элемента
	 */
	protected $parentCode;

	/**
	 * @var string $parentName Наименование родительского элемента
	 */
	protected $parentName;

	/**
	 * @var Town $town Населенный пункт
	 */
	protected $town;

	/**
	 * @var string $address Адрес
	 */
	protected $address;

	/**
	 * @var string $phone Телефоны ПВЗ
	 */
	protected $phone;

	/**
	 * @var string $comment Дополнительная информация
	 */
	protected $comment;

	/**
	 * @var string $workTime Режим работы
	 */
	protected $workTime;

	/**
	 * @var string $travelDescription Описание местонахождения ПВЗ или пути к нему
	 */
	protected $travelDescription;

	/**
	 * @var float $maxWeight Максимальный вес, с которым работает ПВЗ
	 */
	protected $maxWeight;

	/**
	 * @var bool $acceptCash Признак приема наличных
	 */
	protected $acceptCash;

	/**
	 * @var bool $acceptCard Признак приема банковских карт
	 */
	protected $acceptCard;

	/**
	 * @var bool $acceptFitting Наличие примерки
	 */
	protected $acceptFitting;

	/**
	 * @var bool $acceptIndividuals Признак доступности физическим лицам
	 */
	protected $acceptIndividuals;

	/**
	 * @var float $latitude Широта
	 */
	protected $latitude;

	/**
	 * @var float $longitude Долгота
	 */
    protected $longitude;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		if (isset($xml->town)) {
			$town = Town::getFromXml($xml->town, false);
		}

		$params = [
			'code'              => self::extractXmlValue($xml, 'code', $fromNode),
			'clientCode'        => self::extractXmlValue($xml, 'clientcode', $fromNode),
			'name'              => self::extractXmlValue($xml, 'name', $fromNode),
			'parentCode'        => self::extractXmlValue($xml, 'parentcode', $fromNode),
			'parentName'        => self::extractXmlValue($xml, 'parentname', $fromNode),
			'address'           => self::extractXmlValue($xml, 'address', $fromNode),
			'phone'             => self::extractXmlValue($xml, 'phone', $fromNode),
			'comment'           => self::extractXmlValue($xml, 'comment', $fromNode),
			'workTime'          => self::extractXmlValue($xml, 'worktime', $fromNode),
			'travelDescription' => self::extractXmlValue($xml, 'traveldescription', $fromNode),
			'maxWeight'         => self::extractXmlValue($xml, 'maxweight', $fromNode, 'float'),
			'latitude'          => self::extractXmlValue($xml, 'latitude', $fromNode, 'float'),
			'longitude'         => self::extractXmlValue($xml, 'longitude', $fromNode, 'float'),
			'acceptCash'        => isset($xml->acceptcash)        ? (self::extractXmlValue($xml, 'acceptcash', $fromNode) === 'YES') : null,
			'acceptCard'        => isset($xml->acceptcard)        ? (self::extractXmlValue($xml, 'acceptcard', $fromNode) === 'YES') : null,
			'acceptFitting'     => isset($xml->acceptfitting)     ? (self::extractXmlValue($xml, 'acceptfitting', $fromNode) === 'YES') : null,
			'acceptIndividuals' => isset($xml->acceptindividuals) ? (self::extractXmlValue($xml, 'acceptindividuals', $fromNode) === 'YES') : null,
			'town'              => $town ?? null,
		];

		return new Pvz($params);
	}

	/**
	 * @return string Код ПВЗ в системе
	 */ 
	public function getCode(): ?string
	{
		return $this->code;
	}

	/**
	 * @param string $code Код ПВЗ в системе
	 * @return self
	 */ 
	public function setCode(string $code): self
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return string Код ПВЗ используемый компанией-подрядчиком
	 */ 
	public function getClientCode(): ?string
	{
		return $this->clientCode;
	}

	/**
	 * @return string Наименование
	 */ 
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return string Код родительского элемента
	 */ 
	public function getParentCode(): ?string
	{
		return $this->parentCode;
	}

	/**
	 * @return string Наименование родительского элемента
	 */ 
	public function getParentName(): ?string
	{
		return $this->parentName;
	}

	/**
	 * @return Town Населенный пункт
	 */ 
	public function getTown(): ?Town
	{
		return $this->town;
	}

	/**
	 * @return string Адрес
	 */ 
	public function getAddress(): ?string
	{
		return $this->address;
	}

	/**
	 * @return string Телефоны ПВЗ
	 */ 
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @return string Дополнительная информация
	 */ 
	public function getComment(): ?string
	{
		return $this->comment;
	}

	/**
	 * @return string Режим работы
	 */ 
	public function getWorkTime(): ?string
	{
		return $this->workTime;
	}

	/**
	 * @return string Описание местонахождения ПВЗ или пути к нему
	 */ 
	public function getTravelDescription(): ?string
	{
		return $this->travelDescription;
	}

	/**
	 * @return float Максимальный вес, с которым работает ПВЗ
	 */ 
	public function getMaxWeight(): ?float
	{
		return $this->maxWeight;
	}

	/**
	 * @return bool Признак приема наличных
	 */ 
	public function getAcceptCash(): ?bool
	{
		return $this->acceptCash;
	}

	/**
	 * @return bool Признак приема банковских карт
	 */ 
	public function getAcceptCard(): ?bool
	{
		return $this->acceptCard;
	}

	/**
	 * @return bool Наличие примерки
	 */ 
	public function getAcceptFitting(): ?bool
	{
		return $this->acceptFitting;
	}

	/**
	 * @return bool Признак доступности физическим лицам
	 */ 
	public function getAcceptIndividuals(): ?bool
	{
		return $this->acceptIndividuals;
	}

	/**
	 * @return float Широта
	 */ 
	public function getLatitude(): ?float
	{
		return $this->latitude;
	}

    /**
     * @return float Долгота
     */ 
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
}