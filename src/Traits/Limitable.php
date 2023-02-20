<?php

namespace Measoft\Traits;

use SimpleXMLElement;

trait Limitable
{
    /** @var int $offset Задает номер записи результата, начиная с которой выдавать ответ */
    private $offset = 0;

    /** @var int $limit Задает количество записей результата, которые нужно вернуть */
    private $limit = 10000;

    /** @var boolean $countAll YES указывает на необходимость подсчета общего количества найденных совпадений */
    private $countAll = false;

    /**
     * Задать номер записи результата, начиная с которой выдавать ответ
     *
     * @param int $offset Номер записи
     *
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
     *
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
     *
     * @return self
     */
    public function countAll(bool $countAll = true): self
    {
        $this->countAll = $countAll;

        return $this;
    }

    public function buildLimitXML(SimpleXMLElement $xml)
    {
        $limit = $xml->addChild('limit');

        if ($limit) {
            $limit->addChild('limitfrom', $this->offset);
            $limit->addChild('limitcount', $this->limit);
            $limit->addChild('countall', $this->countAll ? 'YES' : null);
        }
    }
}
