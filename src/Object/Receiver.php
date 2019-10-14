<?php

namespace Measoft\Object;

class Receiver extends AbstractObject
{
	/**
	 * @var string $company Название компании получателя
	 */
	protected $company;

	/**
	 * @var string $person Контактное лицо получателя
	 */
	protected $person;

	/**
	 * @var string $phone Телефон, Email
	 */
	protected $phone;

	/**
	 * @var array $contacts Дополнительные контакты
	 */
	protected $contacts;

	/**
	 * @var string $zipcode Почтовый индекс
	 */
	protected $zipcode;

	/**
	 * @var Town $town Город
	 */
	protected $town;

	/**
	 * @var string $address Адрес
	 */
	protected $address;

	/**
	 * @var string $date Дата забора ("YYYY-MM-DD")
	 */
	protected $date;

	/**
	 * @var string $timeMin Минимальное время забора ("HH:MM")
	 */
	protected $timeMin;

	/**
	 * @var string $timeMax Максимальное время забора ("HH:MM")
	 */
	protected $timeMax;

	/**
	 * @var array $coordinates Координаты
	 */
	protected $coordinates;

	/**
	 * @var Pvz $pvz Пункт самовывоза
	 */
	protected $pvz;

	public static function getFromXml(\SimpleXMLElement $xml, bool $fromNode = true): self
	{
		if (isset($xml->contacts)) {
			foreach ($xml->contacts->children() as $contact) {
				$contacts[] = [
					'name'  => $contact->getName(),
					'value' => (string) $contact,
				];
			}
		}

		if (isset($xml->town)) {
			$town = Town::getFromXml($xml->town, false);
		}

		if (isset($xml->pvz)) {
			$pvz = Pvz::getFromXml($xml->pvz);
		}

		if (isset($xml->coords)) {
			$coordinates = [
				'latitude'  => self::extractXmlValue($xml->coords, 'lat', false, 'float'),
				'longitude' => self::extractXmlValue($xml->coords, 'lon', false, 'float'),
			];
		}

		$params = [
			'company'     => self::extractXmlValue($xml, 'company', $fromNode),
			'person'      => self::extractXmlValue($xml, 'person', $fromNode),
			'phone'       => self::extractXmlValue($xml, 'phone', $fromNode),
			'zipcode'     => self::extractXmlValue($xml, 'zipcode', $fromNode),
			'address'     => self::extractXmlValue($xml, 'address', $fromNode),
			'date'        => self::extractXmlValue($xml, 'date', $fromNode),
			'timeMin'     => self::extractXmlValue($xml, 'time_min', $fromNode),
			'timeMax'     => self::extractXmlValue($xml, 'time_max', $fromNode),
			'contacts'    => $contacts    ?? [],
			'coordinates' => $coordinates ?? [],
			'town'        => $town        ?? null,
			'pvz'         => $pvz         ?? null,
		];

		return new Receiver($params);
	}

	/**
	 * @return string Название компании получателя
	 */ 
	public function getCompany(): ?string
	{
		return $this->company;
	}

	/**
	 * @return string Контактное лицо получателя
	 */ 
	public function getPerson(): ?string
	{
		return $this->person;
	}

	/**
	 * @return string Телефон, Email
	 */ 
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @return array Дополнительные контакты
	 */ 
	public function getContacts(): array
	{
		return $this->contacts;
	}

	/**
	 * @return string Почтовый индекс
	 */ 
	public function getZipcode(): ?string
	{
		return $this->zipcode;
	}

	/**
	 * @return Town Город
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
	 * @return string Дата забора ("YYYY-MM-DD")
	 */ 
	public function getDate(): ?string
	{
		return $this->date;
	}

	/**
	 * @return string Минимальное время доставки ("HH:MM")
	 */ 
	public function getTimeMin(): ?string
	{
		return $this->timeMin;
	}

	/**
	 * @return string Максимальное время доставки ("HH:MM")
	 */ 
	public function getTimeMax(): ?string
	{
		return $this->timeMax;
	}

	/**
	 * @return array Координаты
	 */ 
	public function getCoordinates(): array
	{
		return $this->coordinates;
	}

	/**
	 * @return Pvz Пункт самовывоза
	 */ 
	public function getPvz(): ?Pvz
	{
		return $this->pvz;
	}
}