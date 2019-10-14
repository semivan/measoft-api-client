<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Street;

class StreetSearchOperation extends AbstractOperation
{
	/**
	 * @var string $town Поиск по населенному пункту (Название или код)
	 */
	private $town;

	/**
	 * @var string $nameContains Поиск улиц, название которых содержит указанную строку
	 */
	private $nameContains;

	/**
	 * @var string $nameStarts Поиск улиц, название которых начинается с указанной строки
	 */
	private $nameStarts;

	/**
	 * @var string $name Поиск улиц, название которых соответствует указанной строке
	 */
	private $name;

	/**
	 * @var string $fullName Поиск улиц, название вместе с типом улицы которых соответствует указанной строке
	 */
	private $fullName;

	/**
	 * @var int $offset Задает номер записи результата, начиная с которой выдавать ответ
	 */
	private $offset = 0;

	/**
	 * @var int $limit Задает количество записей результата, которые нужно вернуть
	 */
	private $limit = 10000;

	/**
	 * @var string $countAll YES указывает на необходимость подсчета общего количества найденных совпадений
	 */
	private $countAll;

	/**
	 * Поиск по населенному пункту
	 *
	 * @param string $town Название или код населенного пункта
	 * @return self
	 */ 
	public function town(string $town): self
	{
		$this->town = $town;

		return $this;
	}

	/**
	 * Поиск улиц, название которых содержит указанную строку
	 *
	 * @param string $nameContains Строка
	 * @return self
	 */ 
	public function nameContains(string $nameContains): self
	{
		$this->nameContains = $nameContains;

		return $this;
	}

	/**
	 * Поиск улиц, название которых начинается с указанной строки
	 *
	 * @param string $nameStarts Строка
	 * @return self
	 */ 
	public function nameStarts(string $nameStarts): self
	{
		$this->nameStarts = $nameStarts;

		return $this;
	}

	/**
	 * Поиск улиц, название которых соответствует указанной строке
	 *
	 * @param string $name Строка
	 * @return self
	 */ 
	public function name(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Поиск улиц, название вместе с типом улицы которых соответствует указанной строке
	 *
	 * @param string $fullName Строка
	 * @return self
	 */ 
	public function fullName(string $fullName): self
	{
		$this->fullName = $fullName;

		return $this;
	}

	/**
	 * Задать номер записи результата, начиная с которой выдавать ответ
	 *
	 * @param int $offset Номер записи
	 * @return self
	 */ 
	public function offset(int $offset): self
	{
		$this->offset = $offset;

		return $this;
	}

	/**
	 * Задаеть количество записей результата, которые нужно вернуть
	 *
	 * @param int $limit Количество записей результата
	 * @return self
	 */ 
	public function limit(int $limit): self
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Установить подсчет общего количества найденных совпадений
	 *
	 * @param bool $countAll Вести подсчет общего количества найденных совпадений
	 * @return self
	 */ 
	public function countAll(bool $countAll = true): self
	{
		$this->countAll = $countAll === true ? 'YES' : null;

		return $this;
	}

	/**
	 * Сформировать XML
	 *
	 * @return \SimpleXMLElement
	 */
	private function buildXml(): \SimpleXMLElement
	{
		$xml        = $this->createXml('streetlist');
		$conditions = $xml->addChild('conditions');
		$limit      = $xml->addChild('limit');
		
		$conditions->addChild('town', $this->town);
		$conditions->addChild('namecontains', $this->nameContains);
		$conditions->addChild('namestarts', $this->nameStarts);
		$conditions->addChild('name', $this->name);
		$conditions->addChild('fullname', $this->fullName);
		
		$limit->addChild('limitfrom', $this->offset);
		$limit->addChild('limitcount', $this->limit);
		$limit->addChild('countall', $this->countAll);

		return $xml;
	}

	/**
	 * Поиск по заданным условиям
	 *
	 * @return Street[]
	 */
	public function search(): array
	{
		if (!$this->town) {
			throw new MeasoftException('Не указан населенный пункт');
		}

		$response = $this->request($this->buildXml(), false);

		if (!$response->isSuccess()) {
			throw new MeasoftException($response->getError());
		}

		$resultXml = $response->getXml();

		foreach ($resultXml as $item) {
			$result[] = Street::getFromXml($item);
		}

		return $result ?? [];
	}
}