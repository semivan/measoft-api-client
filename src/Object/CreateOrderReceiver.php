<?php

namespace Measoft\Object;

class CreateOrderReceiver
{
	/**
	 * @var string $company Название компании получателя
	 */ 
	private $company;

	/**
	 * @var string $person Контактное лицо получателя
	 */ 
	private $person;

	/**
	 * @var string $phone Телефон, Email
	 */ 
	private $phone;

	/**
	 * @var string $zipcode Почтовый индекс
	 */
	private $zipcode;

	/**
	 * @var string $town Город
	 */ 
	private $town;

	/**
	 * @var string $regionCode Код региона
	 */
	private $regionCode;

	/**
	 * @var string $address Адрес
	 */ 
	private $address;

	/**
	 * @var string $date Дата забора ("YYYY-MM-DD")
	 */ 
	private $date;

	/**
	 * @var string $timeMin Минимальное время забора ("HH:MM")
	 */ 
	private $timeMin;

	/**
	 * @var string $timeMax Максимальное время забора ("HH:MM")
	 */ 
	private $timeMax;

	/**
	 * @var string $pvzCode Код пункта самовывоза
	 */
	private $pvzCode;

	/**
	 * @return string Название компании получателя
	 */ 
	public function getCompany(): ?string
	{
		return $this->company;
	}

	/**
	 * @param string $company Название компании получателя
	 * @return self
	 */ 
	public function setCompany(string $company): self
	{
		$this->company = $company;

		return $this;
	}

	/**
	 * @return string Контактное лицо получателя
	 */ 
	public function getPerson(): ?string
	{
		return $this->person;
	}

	/**
	 * @param string $person Контактное лицо получателя
	 * @return self
	 */ 
	public function setPerson(string $person): self
	{
		$this->person = $person;

		return $this;
	}

	/**
	 * @return string Телефон, Email
	 */ 
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @param string $phone Телефон, Email
	 * @return self
	 */ 
	public function setPhone(string $phone): self
	{
		$this->phone = $phone;

		return $this;
	}

	/**
	 * @return string Почтовый индекс
	 */ 
	public function getZipcode(): ?string
	{
		return $this->zipcode;
	}

	/**
	 * @param string $zipcode Почтовый индекс
	 * @return self
	 */ 
	public function setZipcode(string $zipcode): self
	{
		$this->zipcode = $zipcode;

		return $this;
	}

	/**
	 * @return string Город
	 */ 
	public function getTown(): ?string
	{
		return $this->town;
	}

	/**
	 * @param string $town Город
	 * @return self
	 */ 
	public function setTown(string $town): self
	{
		$this->town = $town;

		return $this;
	}

	/**
	 * @return string Код региона
	 */ 
	public function getRegionCode(): ?string
	{
		return $this->regionCode;
	}

	/**
	 * @param string $regionCode Код региона
	 * @return self
	 */ 
	public function setRegionCode(string $regionCode): self
	{
		$this->regionCode = $regionCode;

		return $this;
	}

	/**
	 * @return string Адрес
	 */ 
	public function getAddress(): ?string
	{
		return $this->address;
	}

	/**
	 * @param string $address Адрес
	 * @return self
	 */ 
	public function setAddress(string $address): self
	{
		$this->address = $address;

		return $this;
	}

	/**
	 * @return string Дата забора ("YYYY-MM-DD")
	 */ 
	public function getDate(): ?string
	{
		return $this->date;
	}

	/**
	 * @param string $date Дата забора ("YYYY-MM-DD")
	 * @return self
	 */ 
	public function setDate(string $date): self
	{
		$this->date = $date;

		return $this;
	}

	/**
	 * @return string Минимальное время забора ("HH:MM")
	 */ 
	public function getTimeMin(): ?string
	{
		return $this->timeMin;
	}

	/**
	 * @param string $timeMin Минимальное время забора ("HH:MM")
	 * @return self
	 */ 
	public function setTimeMin(string $timeMin): self
	{
		$this->timeMin = $timeMin;

		return $this;
	}

	/**
	 * @return string Максимальное время забора ("HH:MM")
	 */ 
	public function getTimeMax(): ?string
	{
		return $this->timeMax;
	}

	/**
	 * @param string $timeMax Максимальное время забора ("HH:MM")
	 * @return self
	 */ 
	public function setTimeMax(string $timeMax): self
	{
		$this->timeMax = $timeMax;

		return $this;
	}

	/**
	 * @return string Код пункта самовывоза
	 */ 
	public function getPvzCode(): ?string
	{
		return $this->pvzCode;
	}

	/**
	 * @param string $pvzCode Код пункта самовывоза
	 * @return self
	 */ 
	public function setPvzCode(string $pvzCode): self
	{
		$this->pvzCode = $pvzCode;

		return $this;
	}
}
