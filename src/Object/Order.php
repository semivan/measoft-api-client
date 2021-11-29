<?php

namespace Measoft\Object;

use Measoft\MeasoftException;
use SimpleXMLElement;

class Order extends AbstractObject
{
    const STATUSES = [
        'NEW'              => 'Новый',
        'PICKUP'           => 'Забран у отправителя',
        'ACCEPTED'         => 'Получен складом',
        'INVENTORY'        => 'Инвентаризация',
        'DEPARTURING'      => 'Планируется отправка',
        'DEPARTURE'        => 'Отправлено со склада',
        'DELIVERY'         => 'Выдан курьеру на доставку',
        'COURIERDELIVERED' => 'Доставлен (предварительно)',
        'COMPLETE'         => 'Доставлен',
        'PARTIALLY'        => 'Доставлен частично',
        'COURIERRETURN'    => 'Курьер вернул на склад',
        'CANCELED'         => 'Не доставлен (Возврат/Отмена)',
        'RETURNING'        => 'Планируется возврат',
        'RETURNED'         => 'Возвращен',
        'CONFIRM'          => 'Согласована доставка',
        'DATECHANGE'       => 'Перенос',
        'NEWPICKUP'        => 'Создан забор',
        'UNCONFIRM'        => 'Не удалось согласовать доставку',
        'PICKUPREADY'      => 'Готов к выдаче',
        'AWAITING_SYNC'    => 'Ожидание синхронизации',
    ];

    /** @var string $orderNumber Номер заказа */
    protected $orderNumber;

    /** @var string $awb Номер накладной в системе курьерской службы */
    protected $awb;

    /** @var string $orderNumber2 Номер накладной в подсистеме срочной доставки курьерской службы */
    protected $orderNumber2;

    /** @var string $orderCode Внутренний код заказа в системе */
    protected $orderCode;

    /** @var string $givenCode Внутренний код заказа в системе */
    protected $givenCode;

    /** @var string $barcode Штрих-код заказа */
    protected $barcode;

    /** @var Sender $sender Отправитель */
    protected $sender;

    /** @var Receiver $receiver Получатель */
    protected $receiver;

    /** @var float $weight Общий вес заказа в килограммах */
    protected $weight;

    /** @var float $returnWeight Общий вес возврата заказа в килограммах */
    protected $returnWeight;

    /** @var int $quantity Количество мест */
    protected $quantity;

    /** @var string $payType Тип оплаты заказа получателем */
    protected $payType;

    /** @var int $service Режим доставки */
    protected $service;

    /** @var int $returnService Режим возврата */
    protected $returnService;

    /** @var string $type Тип корреспонденции */
    protected $type;

    /** @var string $returnType Тип возвратной корреспонденции */
    protected $returnType;

    /** @var string $waitTime Время ожидания курьера */
    protected $waitTime;

    /** @var float $price Сумма заказа */
    protected $price;

    /** @var bool $printCheck Проверить печать */
    protected $printCheck;

    /** @var float $declaredPrice Объявленная ценность */
    protected $declaredPrice;

    /** @var string $enclosure Вложение */
    protected $enclosure;

    /** @var string $instruction Поручение - Примечание */
    protected $instruction;

    /** @var array $currentCoordinates Текущие координаты заказа */
    protected $currentCoordinates;

    /** @var Courier $courier Курьер */
    protected $courier;

    /** @var array $deliveryPrice Стоимость услуг в валюте расчетов с клиентом */
    protected $deliveryPrice;

    /** @var bool $receiverPays Признак оплаты стоимости доставки получателем */
    protected $receiverPays;

    /** @var Status $status Статус доставки */
    protected $status;

    /** @var Status[] $statusHistory История статусов доставки */
    protected $statusHistory;

    /** @var string $customStateCode Код внутреннего статуса курьерской службы */
    protected $customStateCode;

    /** @var string $clientStateCode Код статуса клиента */
    protected $clientStateCode;

    /** @var string $deliveredTo Данные о вручении, либо причина недоставки */
    protected $deliveredTo;

    /** @var string $deliveredDate Дата вручения */
    protected $deliveredDate;

    /** @var string $deliveredTime Время вручения */
    protected $deliveredTime;

    /** @var string $externalSystemCode Код заказа во внешней системе */
    protected $externalSystemCode;

    /** @var Item[] $items Вложения */
    protected $items;

    /** @var Package[] $packages Места */
    protected $packages;

    /**
     * @param SimpleXMLElement $xml
     * @param bool $fromNode
     * @return static
     * @throws MeasoftException
     */
    public static function getFromXml(SimpleXMLElement $xml, bool $fromNode = true): self
    {
        $params = [
            'orderNumber'        => self::extractXmlValue($xml, 'orderno', false),
            'awb'                => self::extractXmlValue($xml, 'awb', false),
            'orderNumber2'       => self::extractXmlValue($xml, 'orderno2', false),
            'orderCode'          => self::extractXmlValue($xml, 'ordercode', false),
            'givenCode'          => self::extractXmlValue($xml, 'givencode', false),
            'barcode'            => self::extractXmlValue($xml, 'barcode', true),
            'weight'             => self::extractXmlValue($xml, 'weight', true, 'float'),
            'returnWeight'       => self::extractXmlValue($xml, 'return_weight', true, 'float'),
            'quantity'           => self::extractXmlValue($xml, 'quantity', true, 'int'),
            'payType'            => self::extractXmlValue($xml, 'paytype', true),
            'service'            => self::extractXmlValue($xml, 'service', true, 'int'),
            'returnService'      => self::extractXmlValue($xml, 'return_service', true, 'int'),
            'type'               => self::extractXmlValue($xml, 'type', true),
            'returnType'         => self::extractXmlValue($xml, 'return_type', true),
            'waitTime'           => self::extractXmlValue($xml, 'waittime', true),
            'price'              => self::extractXmlValue($xml, 'price', true, 'float'),
            'declaredPrice'      => self::extractXmlValue($xml, 'inshprice', true, 'float'),
            'enclosure'          => self::extractXmlValue($xml, 'enclosure', true),
            'instruction'        => self::extractXmlValue($xml, 'instruction', true),
            'customStateCode'    => self::extractXmlValue($xml, 'customstatecode', true),
            'clientStateCode'    => self::extractXmlValue($xml, 'clientstatecode', true),
            'deliveredTo'        => self::extractXmlValue($xml, 'deliveredto', true),
            'deliveredDate'      => self::extractXmlValue($xml, 'delivereddate', true),
            'deliveredTime'      => self::extractXmlValue($xml, 'deliveredtime', true),
            'externalSystemCode' => self::extractXmlValue($xml, 'outstrbarcode', true),
            'printCheck'         => isset($xml->print_check) ? (self::extractXmlValue($xml, 'print_check', true) === 'YES') : null,
            'receiverPays'       => isset($xml->receiverpays) ? (self::extractXmlValue($xml, 'receiverpays', true) === 'YES') : null,
        ];

        $params['sender']   = isset($xml->sender)   ? Sender::getFromXml($xml->sender) : null;
        $params['receiver'] = isset($xml->receiver) ? Receiver::getFromXml($xml->receiver) : null;
        $params['courier']  = isset($xml->courier)  ? Courier::getFromXml($xml->courier) : null;
        $params['status']   = isset($xml->status)   ? Status::getFromXml($xml->status, false) : null;

        if (isset($xml->items)) {
            foreach (($xml->items->children() ?? []) as $orderItem) {
                $params['items'][] = Item::getFromXml($orderItem, false);
            }
        }

        if (isset($xml->statushistory)) {
            foreach (($xml->statushistory->children() ?? []) as $statusHistory) {
                $params['statusHistory'][] = Status::getFromXml($statusHistory, false);
            }
        }

        if (isset($xml->currcoords)) {
            $params['currentCoordinates'] = [
                'latitude'   => self::extractXmlValue($xml->currcoords, 'lat', false, 'float'),
                'longitude'  => self::extractXmlValue($xml->currcoords, 'lon', false, 'float'),
                'accuracy'   => self::extractXmlValue($xml->currcoords, 'accuracy', false, 'float'),
                'updated_at' => self::extractXmlValue($xml->currcoords, 'RequestDateTime', false),
            ];
        }

        if (isset($xml->deliveryprice)) {
            $params['deliveryPrice'] = [
                'total'    => self::extractXmlValue($xml->deliveryprice, 'total', false, 'float'),
                'delivery' => self::extractXmlValue($xml->deliveryprice, 'delivery', false, 'float'),
                'return'   => self::extractXmlValue($xml->deliveryprice, 'return', false, 'float'),
                'services' => [],
            ];

            foreach ($xml->deliveryprice->children() as $service) {
                $params['deliveryPrice']['services'][] = [
                    'name'  => (string) $service,
                    'code'  => self::extractXmlValue($service, 'code', false, 'float'),
                    'price' => self::extractXmlValue($service, 'price', false),
                ];
            }
        }

        if (isset($xml->packages)) {
            foreach (($xml->packages->children() ?? []) as $package) {
                $params['packages'][] = Package::getFromXml($package, false);
            }
        }

        return new Order($params);
    }

    /** @return string|null Номер заказа */
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    /** @return string|null Номер накладной в системе курьерской службы */
    public function getAwb(): ?string
    {
        return $this->awb;
    }

    /** @return string|null Номер накладной в подсистеме срочной доставки курьерской службы */
    public function getOrderNumber2(): ?string
    {
        return $this->orderNumber2;
    }

    /** @return string|null Внутренний код заказа в системе */
    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    /** @return string|null Внутренний код заказа в системе */
    public function getGivenCode(): ?string
    {
        return $this->givenCode;
    }

    /** @return string|null Штрих-код заказа */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /** @return Sender|null Отправитель */
    public function getSender(): ?Sender
    {
        return $this->sender;
    }

    /** @return Receiver|null Получатель */
    public function getReceiver(): ?Receiver
    {
        return $this->receiver;
    }

    /** @return float|null Общий вес заказа в килограммах */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /** @return float|null Общий вес возврата заказа в килограммах */
    public function getReturnWeight(): ?float
    {
        return $this->returnWeight;
    }

    /** @return int|null Количество мест */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /** @return string|null Тип оплаты заказа получателем */
    public function getPayType(): ?string
    {
        return $this->payType;
    }

    /** @return int|null Режим доставки */
    public function getService(): ?int
    {
        return $this->service;
    }

    /** @return int|null Режим возврата */
    public function getReturnService(): ?int
    {
        return $this->returnService;
    }

    /** @return string|null Тип корреспонденции */
    public function getType(): ?string
    {
        return $this->type;
    }

    /** @return string|null Тип возвратной корреспонденции */
    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    /** @return string|null Время ожидания курьера */
    public function getWaitTime(): ?string
    {
        return $this->waitTime;
    }

    /** @return float|null Сумма заказа */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /** @return bool|null Проверить печать */
    public function getPrintCheck(): ?bool
    {
        return $this->printCheck;
    }

    /** @return float|null Объявленная ценность */
    public function getDeclaredPrice(): ?float
    {
        return $this->declaredPrice;
    }

    /** @return string|null Вложение */
    public function getEnclosure(): ?string
    {
        return $this->enclosure;
    }

    /** @return string|null Поручение - Примечание */
    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    /** @return array Текущие координаты заказа */
    public function getCurrentCoordinates(): array
    {
        return $this->currentCoordinates ?? [];
    }

    /** @return Courier|null Курьер */
    public function getCourier(): ?Courier
    {
        return $this->courier;
    }

    /** @return array Стоимость услуг в валюте расчетов с клиентом */
    public function getDeliveryPrice(): array
    {
        return $this->deliveryPrice ?? [];
    }

    /** @return bool|null Признак оплаты стоимости доставки получателем */
    public function getReceiverPays(): ?bool
    {
        return $this->receiverPays;
    }

    /** @return Status|null Статус доставки */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /** @return Status[] История статусов доставки */
    public function getStatusHistory(): array
    {
        return $this->statusHistory ?? [];
    }

    /** @return string|null Код внутреннего статуса курьерской службы */
    public function getCustomStateCode(): ?string
    {
        return $this->customStateCode;
    }

    /** @return string|null Код статуса клиента */
    public function getClientStateCode(): ?string
    {
        return $this->clientStateCode;
    }

    /** @return string|null Данные о вручении, либо причина недоставки */
    public function getDeliveredTo(): ?string
    {
        return $this->deliveredTo;
    }

    /** @return string|null Дата вручения */
    public function getDeliveredDate(): ?string
    {
        return $this->deliveredDate;
    }

    /** @return string|null Время вручения */
    public function getDeliveredTime(): ?string
    {
        return $this->deliveredTime;
    }

    /** @return string|null Код заказа во внешней системе */
    public function getExternalSystemCode(): ?string
    {
        return $this->externalSystemCode;
    }

    /** @return Item[] Вложения */
    public function getItems(): array
    {
        return $this->items ?? [];
    }

    /** @return Package[] Места */
    public function getPackages(): array
    {
        return $this->packages ?? [];
    }
}