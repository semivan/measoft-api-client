<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\Item;
use SimpleXMLElement;

class ItemSearchOperation extends AbstractOperation
{
    /** @var string $code Поиск по внутреннему коду системы */
    private $code;

    /** @var string $article Поиск по артикулу */
    private $article;

    /** @var string $barcode Поиск по штрих-коду */
    private $barcode;

    /** @var string $nameContains Поиск товаров, название которых содержит указанную строку */
    private $nameContains;

    /** @var string $nameStarts Поиск товаров, название которых начинается с указанной строки */
    private $nameStarts;

    /** @var string $name Поиск товаров, название которых соответствует указанной строке */
    private $name;

    /** @var bool $inStock Поиск по наличию на складе (true - в наличии, false - не в наличии, null - все) */
    private $inStock;

    /** @var int $offset Задает номер записи результата, начиная с которой выдавать ответ */
    private $offset = 0;

    /** @var int $limit Задает количество записей результата, которые нужно вернуть */
    private $limit = 10000;

    /** @var string $countAll YES указывает на необходимость подсчета общего количества найденных совпадений */
    private $countAll;

    /**
     * Поиск по внутреннему коду системы
     *
     * @param string $code Код
     * @return self
     */
    public function code(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Поиск по артикулу
     *
     * @param string $article Артикул
     * @return self
     */
    public function article(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Поиск по штрих-коду
     *
     * @param string $barcode Штрих-код
     * @return self
     */
    public function barcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * Поиск товаров, название которых содержит указанную строку
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
     * Поиск товаров, название которых начинается с указанной строки
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
     * Поиск товаров, название которых соответствует указанной строке
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
     * Поиск по наличию на складе
     *
     * @param bool|null $inStock (true - в наличии, false - не в наличии, null - все)
     * @return self
     */
    public function inStock(?bool $inStock): self
    {
        $this->inStock = $inStock;

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
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml        = $this->createXml('itemlist');
        $codesearch = $xml->addChild('codesearch');
        $conditions = $xml->addChild('conditions');
        $limit      = $xml->addChild('limit');

        $codesearch->addChild('code', $this->code);
        $codesearch->addChild('article', $this->article);
        $codesearch->addChild('barcode', $this->barcode);

        $conditions->addChild('namecontains', $this->nameContains);
        $conditions->addChild('namestarts', $this->nameStarts);
        $conditions->addChild('name', $this->name);
        $conditions->addChild('quantity', $this->inStock === true ? 'EXISTING_ONLY' : ($this->inStock === false ? 'NOT_EXISTING_ONLY' : 'ALL'));

        $limit->addChild('limitfrom', $this->offset);
        $limit->addChild('limitcount', $this->limit);
        $limit->addChild('countall', $this->countAll);

        return $xml;
    }

    /**
     * Поиск по заданным условиям
     *
     * @return Item[]
     * @throws MeasoftException
     */
    public function search(): array
    {
        $response = $this->request($this->buildXml());

        if (!$response->isSuccess()) {
            throw new MeasoftException($response->getError());
        }

        $resultXml = $response->getXml();

        foreach ($resultXml as $item) {
            $result[] = Item::getFromXml($item);
        }

        return $result ?? [];
    }
}