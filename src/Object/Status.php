<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Status extends AbstractObject
{
    // Ожидает синхронизации. Данные заказа пока не появились в службе доставки
    private const STATUS_AWAITING_SYNC = 'AWAITING_SYNC';

    // Успешно создан, передан в службу доставки
    private const STATUS_NEW = 'NEW';

    // Создан забор
    private const STATUS_NEWPICKUP = 'NEWPICKUP';

    // Забран у отправителя
    private const STATUS_PICKUP = 'PICKUP';

    // Скомплектован на складе фулфилмента
    private const STATUS_WMSASSEMBLED = 'WMSASSEMBLED';

    // Разукомплектован на склад фулфилмента
    private const STATUS_WMSDISASSEMBLED = 'WMSDISASSEMBLED';

    // Получен складом
    private const STATUS_ACCEPTED = 'ACCEPTED';

    // Производится таможенный контроль
    private const STATUS_CUSTOMSPROCESS = 'CUSTOMSPROCESS';

    // Таможенный контроль произведен
    private const STATUS_CUSTOMSFINISHED = 'CUSTOMSFINISHED';

    // Согласована доставка
    private const STATUS_CONFIRM = 'CONFIRM';

    // Не удалось согласовать доставку
    private const STATUS_UNCONFIRM = 'UNCONFIRM';

    // Планируется отправка со склада на другой склад
    private const STATUS_DEPARTURING = 'DEPARTURING';

    // Отправлено со склада на другой склад
    private const STATUS_DEPARTURE = 'DEPARTURE';

    // Инвентаризация. Убедились в наличии отправления на складе
    private const STATUS_INVENTORY = 'INVENTORY';

    // Готов к выдаче в ПВЗ
    private const STATUS_PICKUPREADY = 'PICKUPREADY';

    // Выдан курьеру на доставку
    private const STATUS_DELIVERY = 'DELIVERY';

    // Доставлен (предварительно, ожидает подтверждения менеджером, чтобы перейти в статус COMPLETE)
    private const STATUS_COURIERDELIVERED = 'COURIERDELIVERED';

    // Частично доставлен (предварительно, ожидает подтверждения менеджером, чтобы перейти в статус PARTIALLY)
    private const STATUS_COURIERPARTIALLY = 'COURIERPARTIALLY';

    // Отказ (предварительно, после этого ожидается COURIERRETURN)
    private const STATUS_COURIERCANCELED = 'COURIERCANCELED';

    // Возвращено курьером. Курьер не смог доставить до получателя и вернул заказ обратно на склад. Это промежуточный статус, после которого менеджер выясняет, нужно ли повторно доставлять (статусы DATECHANGE/DELIVERY) или это окончательная недоставка (CANCELED)
    private const STATUS_COURIERRETURN = 'COURIERRETURN';

    // Перенос даты доставки
    private const STATUS_DATECHANGE = 'DATECHANGE';

    // Доставлен
    private const STATUS_COMPLETE = 'COMPLETE';

    // Доставлен частично
    private const STATUS_PARTIALLY = 'PARTIALLY';

    // Не доставлен (Возврат/Отмена). После этого статуса отправление должны вернуть заказчику, будут статусы RETURNING и RETURNE
    private const STATUS_CANCELED = 'CANCELED';

    // Планируется возврат заказчику (после CANCELED)
    private const STATUS_RETURNING = 'RETURNING';

    // Возвращен заказчику
    private const STATUS_RETURNED = 'RETURNED';

    // Утрачен/утерян
    private const STATUS_LOST = 'LOST';

    // Планируется возврат остатков
    private const STATUS_PARTLYRETURNING = 'PARTLYRETURNING';

    // Остаток возвращен
    private const STATUS_PARTLYRETURNED = 'PARTLYRETURNED';

    // Прибыл на склад перевозчика
    private const STATUS_TRANSACCEPTED = 'TRANSACCEPTED';

    // Забран у перевозчика
    private const STATUS_PICKUPTRANS = 'PICKUPTRANS';

    /** @var string $name Наименование статуса */
    protected $name;

    /** @var string $eventStore Филиал, к которому относится текущий статус */
    protected $eventStore;

    /** @var string $eventTime Время события по часовому поясу места его наступления */
    protected $eventTime;

    /** @var string $createTimeGmt Время по GMT создания записи о смене статуса в БД */
    protected $createTimeGmt;

    /** @var string $message Наименование филиала-получателя, при передаче между филиалами */
    protected $message;

    /** @var string $title Русское наименование статуса */
    protected $title;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'name'          => $fromNode ? self::extractXmlValue($xml, 'name', $fromNode) : (string) $xml,
            'eventStore'    => self::extractXmlValue($xml, 'eventstore', $fromNode),
            'eventTime'     => self::extractXmlValue($xml, 'eventtime', $fromNode),
            'createTimeGmt' => self::extractXmlValue($xml, 'createtimegmt', $fromNode),
            'message'       => self::extractXmlValue($xml, 'message', $fromNode),
            'title'         => self::extractXmlValue($xml, 'title', $fromNode),
        ];

        return new Status($params);
    }

    /** @return string|null Наименование статуса */
    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return string|null Филиал, к которому относится текущий статус */
    public function getEventStore(): ?string
    {
        return $this->eventStore;
    }

    /** @return string|null Время события по часовому поясу места его наступления */
    public function getEventTime(): ?string
    {
        return $this->eventTime;
    }

    /** @return string|null Время по GMT создания записи о смене статуса в БД */
    public function getCreateTimeGmt(): ?string
    {
        return $this->createTimeGmt;
    }

    /** @return string|null Наименование филиала-получателя, при передаче между филиалами */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /** @return string|null Русское наименование статуса */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function isDelivered(): bool
    {
        return $this->name === self::STATUS_COMPLETE;
    }

    public function isDeliveredPartially(): bool
    {
        return in_array($this->name, [self::STATUS_PARTIALLY, self::STATUS_PARTLYRETURNING, self::STATUS_PARTLYRETURNED]);
    }

    public function isCancelled(): bool
    {
        return in_array($this->name, [self::STATUS_CANCELED, self::STATUS_RETURNING, self::STATUS_RETURNED]);
    }

    public function isReturned(): bool
    {
        return in_array($this->name, [self::STATUS_RETURNED, self::STATUS_PARTLYRETURNED]);
    }
}
