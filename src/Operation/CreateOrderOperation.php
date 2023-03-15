<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use Measoft\Object\CreateOrderItem;
use Measoft\Object\CreateOrderReceiver;
use Measoft\Object\CreateOrderSender;
use Measoft\Object\CreateOrderPackage;
use Measoft\Object\Order;
use SimpleXMLElement;

class CreateOrderOperation extends AbstractOperation
{
    /** Типы оплаты */
    const PAYMENT_TYPE_CASH  = 'CASH';
    const PAYMENT_TYPE_CARD  = 'CARD';
    const PAYMENT_TYPE_NONE  = 'NO';
    const PAYMENT_TYPE_OTHER = 'OTHER';

    /** @var string $newFolder Признак нового заказа */
    private $newFolder;

    /** @var string $orderNumber Номер заказа */
    private $orderNumber;

    /** @var string $barcode Штрих-код заказа */
    private $barcode;

    /** @var CreateOrderSender $sender Отправитель */
    private $sender;

    /** @var CreateOrderReceiver $receiver Получатель */
    private $receiver;

    /** @var string $return Признак необходимости возврата */
    private $return;

    /** @var float $weight Общий вес заказа в килограммах */
    private $weight;

    /** @var float $returnWeight Общий вес возврата заказа в килограммах */
    private $returnWeight;

    /** @var int $quantity Количество мест */
    private $quantity;

    /**
     * @var string $payType Тип оплаты заказа получателем
     * CASH   - Наличными при получении (по-умолчанию)
     * CARD   - Картой при получении
     * NO     - Без оплаты. Поле Price будет проигнорировано. (Этот тип оплаты передается, если заказ уже оплачен и не требует инкассации, API добавит в систему товары по нулевой цене. Если необходимо передать общую сумму заказа - можно это сделать в поле <inshprice>, указав объявленную ценность)
     * OTHER  - Прочее (Предусмотрен для того, чтобы оплата поступала непосредственно в курьерскую службу посредством прочих типов оплаты - таких как: вебмани, яденьги, картой на сайте, прочие платежные системы и т.д.)
     * OPTION - На выбор получателя. Этот тип оплаты нельзя передавать с заказом. Он выставляется автоматически в зависимости от настройки клиента.
     */
    private $payType;

    /** @var string $service Режим доставки (тип услуги) */
    private $service;

    /** @var string $returnService Режим возврата (тип услуги) */
    private $returnService;

    /** @var string $type Тип корреспонденции (отправления) */
    private $type;

    /** @var string $returnType Тип возвратной корреспонденции (отправления) */
    private $returnType;

    /** @var float $price Сумма заказа */
    private $price;

    /** @var float $deliveryPrice Сумма доставки */
    private $deliveryPrice;

    /** @var float $declaredPrice Объявленная ценность */
    private $declaredPrice;

    /** @var string $receiverPays Признак оплаты стоимости доставки получателем */
    private $receiverPays;

    /** @var string $enclosure Вложение */
    private $enclosure;

    /** @var string $instruction Поручение - Примечание */
    private $instruction;

    /** @var string $department Подразделение, в котором оформляется заказ */
    private $department;

    /** @var string $pickup Признак оформления забора */
    private $pickup;

    /** @var string $acceptPartially Признак возможности частичного выкупа товаров отправления */
    private $acceptPartially;

    /** @var CreateOrderItem[] $items Вложения */
    private $items = [];

    /** @var CreateOrderPackage[] $packages Места */
    private $packages= [];

    /** @var array $deliverySet Настройка дифференциальной стоимости доставки */
    private $deliverySet;

    /** @var array $deliverySetBelowList Список границ стоимости настроек */
    private $deliverySetBelowList = [];

    /**
     * @param bool $newFolder Признак нового заказа
     * @return self
     */
    public function setNewFolder(bool $newFolder): self
    {
        $this->newFolder = $newFolder ? 'YES' : 'NO';

        return $this;
    }

    /**
     * @param string $orderNumber Номер заказа
     * @return self
     */
    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @param string $barcode Штрих-код заказа
     * @return self
     */
    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @param CreateOrderSender $sender Отправитель
     * @return self
     */
    public function setSender(CreateOrderSender $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @param CreateOrderReceiver $receiver Получатель
     * @return self
     */
    public function setReceiver(CreateOrderReceiver $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @param bool $return Признак необходимости возврата
     * @return self
     */
    public function setReturn(bool $return): self
    {
        $this->return = $return ? 'YES' : 'NO';

        return $this;
    }

    /**
     * @param float $weight Общий вес заказа в килограммах
     * @return self
     */
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @param float $returnWeight Общий вес возврата заказа в килограммах
     * @return self
     */
    public function setReturnWeight(float $returnWeight): self
    {
        $this->returnWeight = $returnWeight;

        return $this;
    }

    /**
     * @param int $quantity Количество мест
     * @return self
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param string $payType Тип оплаты заказа получателем
     * CASH   - Наличными при получении (по-умолчанию)
     * CARD   - Картой при получении
     * NO     - Без оплаты. Поле Price будет проигнорировано. (Этот тип оплаты передается, если заказ уже оплачен и не требует инкассации, API добавит в систему товары по нулевой цене. Если необходимо передать общую сумму заказа - можно это сделать в поле <inshprice>, указав объявленную ценность)
     * OTHER  - Прочее (Предусмотрен для того, чтобы оплата поступала непосредственно в курьерскую службу посредством прочих типов оплаты - таких как: вебмани, яденьги, картой на сайте, прочие платежные системы и т.д.)
     * OPTION - На выбор получателя. Этот тип оплаты нельзя передавать с заказом. Он выставляется автоматически в зависимости от настройки клиента.
     * @return self
     * @throws MeasoftException
     */
    public function setPayType(string $payType): self
    {
        if (!in_array($payType, ['CASH', 'CARD', 'NO', 'OTHER', 'OPTION'])) {
            throw new MeasoftException('Неверный способ оплаты');
        }

        $this->payType = $payType;

        return $this;
    }

    /**
     * @param string $service Режим доставки (тип услуги)
     * @return self
     */
    public function setService(string $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @param string $returnService Режим возврата (тип услуги)
     * @return self
     */
    public function setReturnService(string $returnService): self
    {
        $this->returnService = $returnService;

        return $this;
    }

    /**
     * @param string $type Тип корреспонденции (отправления)
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $returnType Тип возвратной корреспонденции (отправления)
     * @return self
     */
    public function setReturnType(string $returnType): self
    {
        $this->returnType = $returnType;

        return $this;
    }

    /**
     * @param float $price Сумма заказа
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param float $deliveryPrice Сумма доставки
     * @return self
     */
    public function setDeliveryPrice(float $deliveryPrice): self
    {
        $this->deliveryPrice = $deliveryPrice;

        return $this;
    }

    /**
     * @param float $declaredPrice Объявленная ценность
     * @return self
     */
    public function setDeclaredPrice(float $declaredPrice): self
    {
        $this->declaredPrice = $declaredPrice;

        return $this;
    }

    /**
     * @param bool $receiverPays Признак оплаты стоимости доставки получателем
     * @return self
     */
    public function setReceiverPays(bool $receiverPays): self
    {
        $this->receiverPays = $receiverPays ? 'YES' : 'NO';

        return $this;
    }

    /**
     * @param string $enclosure Вложение
     * @return self
     */
    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @param string $instruction Поручение - Примечание
     * @return self
     */
    public function setInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    /**
     * @param string $department Подразделение, в котором оформляется заказ
     * @return self
     */
    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @param bool $pickup Признак оформления забора
     * @return self
     */
    public function setPickup(bool $pickup): self
    {
        $this->pickup = $pickup ? 'YES' : 'NO';

        return $this;
    }

    /**
     * @param bool $acceptPartially Признак возможности частичного выкупа товаров отправления
     * @return self
     */
    public function setAcceptPartially(bool $acceptPartially): self
    {
        $this->acceptPartially = $acceptPartially ? 'YES' : 'NO';

        return $this;
    }

    /**
     * @param CreateOrderItem $item Вложение
     * @return self
     */
    public function addItem(CreateOrderItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param CreateOrderPackage $package Место
     * @return self
     */
    public function addPackage(CreateOrderPackage $package): self
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * Настройка дифференциальной стоимости доставки
     *
     * @param float $abovePrice  Стоимость в случае полного выкупа заказа (действует как "сумма от" последней границы, указанной в теге below_sum)
     * @param float $returnPrice Стоимость в случае возврата заказа
     * @return self
     */
    public function setDeliverySet(float $abovePrice, float $returnPrice): self
    {
        $this->deliverySet = [
            'above_price'  => $abovePrice,
            'return_price' => $returnPrice,
        ];

        return $this;
    }

    /**
     * Добавить границу стоимости настроек
     *
     * @param float $sum   Граница стоимости выкупаемого заказа
     * @param float $price Стоимость выкупаемого заказа до соответствующей границы
     * @return self
     */
    public function addDeliverySetBelow(float $sum, float $price): self
    {
        $this->deliverySetBelowList[] = [
            'sum'   => $sum,
            'price' => $price,
        ];

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    protected function buildXml(): SimpleXMLElement
    {
        $xml           = $this->createXml('neworder');
        $order         = $xml->addChild('order');
        $orderItems    = $order->addChild('items');
        $sender        = $order->addChild('sender');
        $receiver      = $order->addChild('receiver');
        $orderPackages = $order->addChild('packages');

        $xml->addAttribute('newfolder', $this->newFolder);

        $order->addAttribute('orderno', $this->orderNumber);
        $order->addChild('barcode', $this->barcode);
        $order->addChild('return', $this->return);
        $order->addChild('weight', $this->weight);
        $order->addChild('return_weight', $this->returnWeight);
        $order->addChild('quantity', $this->quantity);
        $order->addChild('paytype', $this->payType);
        $order->addChild('service', $this->service);
        $order->addChild('return_service', $this->returnService);
        $order->addChild('type', $this->type);
        $order->addChild('return_type', $this->returnType);
        $order->addChild('price', $this->price);
        $order->addChild('deliveryprice', $this->deliveryPrice);
        $order->addChild('inshprice', $this->declaredPrice);
        $order->addChild('receiverpays', $this->receiverPays);
        $order->addChild('enclosure', $this->enclosure);
        $order->addChild('instruction', $this->instruction);
        $order->addChild('department', $this->department);
        $order->addChild('pickup', $this->pickup);
        $order->addChild('acceptpartially', $this->acceptPartially);

        if ($this->sender) {
            $sender->addChild('company', $this->sender->getCompany());
            $sender->addChild('person', $this->sender->getPerson());
            $sender->addChild('phone', $this->sender->getPhone());
            $sender->addChild('town', $this->sender->getTown());
            $sender->addChild('address', $this->sender->getAddress());
            $sender->addChild('date', $this->sender->getDate());
            $sender->addChild('time_min', $this->sender->getTimeMin());
            $sender->addChild('time_max', $this->sender->getTimeMax());
        }

        if ($this->receiver) {
            $receiver->addChild('company', $this->receiver->getCompany());
            $receiver->addChild('person', $this->receiver->getPerson());
            $receiver->addChild('phone', $this->receiver->getPhone());
            $receiver->addChild('zipcode', $this->receiver->getZipcode());
            $receiver->addChild('address', $this->receiver->getAddress());
            $receiver->addChild('pvz', $this->receiver->getPvzCode());
            $receiver->addChild('date', $this->receiver->getDate());
            $receiver->addChild('time_min', $this->receiver->getTimeMin());
            $receiver->addChild('time_max', $this->receiver->getTimeMax());
            $town = $receiver->addChild('town', $this->receiver->getTown());
            $town->addChild('regioncode', $this->receiver->getRegionCode());
        }

        foreach ($this->items as $item) {
            $orderItem = $orderItems->addChild('item', $item->getName());
            $orderItem->addAttribute('extcode', $item->getExternalCode());
            $orderItem->addAttribute('quantity', $item->getQuantity());
            $orderItem->addAttribute('mass', $item->getWeight());
            $orderItem->addAttribute('retprice', $item->getRetailPrice());
            $orderItem->addAttribute('VATrate', $item->getVatRate());
            $orderItem->addAttribute('barcode', $item->getBarcode());
            $orderItem->addAttribute('article', $item->getArticle());
            $orderItem->addAttribute('volume', $item->getVolume());
            $orderItem->addAttribute('type', $item->getType());
            $orderItem->addAttribute('length', $item->getLength());
            $orderItem->addAttribute('width', $item->getWidth());
            $orderItem->addAttribute('height', $item->getHeight());
        }

        foreach ($this->packages as $package) {
            $orderPackage = $orderPackages->addChild('package', $package->getName());
            $orderPackage->addAttribute('code', $package->getCode());
            $orderPackage->addAttribute('strbarcode', $package->getBarcode());
            $orderPackage->addAttribute('mass', $package->getWeight());
            $orderPackage->addAttribute('message', $package->getMessage());
            $orderPackage->addAttribute('length', $package->getLength());
            $orderPackage->addAttribute('width', $package->getWidth());
            $orderPackage->addAttribute('height', $package->getHeight());
        }

        if ($this->deliverySet) {
            $deliverySet = $order->addChild('deliveryset');
            $deliverySet->addAttribute('above_price', $this->deliverySet['above_price'] ?? null);
            $deliverySet->addAttribute('return_price', $this->deliverySet['return_price'] ?? null);

            foreach ($this->deliverySetBelowList as $below) {
                $deliverySetBelow = $deliverySet->addChild('below');
                $deliverySetBelow->addAttribute('below_sum', $below['sum'] ?? null);
                $deliverySetBelow->addAttribute('price', $below['price'] ?? null);
            }
        }

        return $xml;
    }

    /**
     * Создание заказа
     *
     * @return Order
     * @throws MeasoftException
     */
    public function create(): Order
    {
        foreach ($this->getResults() as $item) {
            $errorCode = intval($item['error'] ?? 0);

            if ($errorCode) {
                throw new MeasoftException($item['errormsgru'] ?? $item['errormsg'] ?? 'Код ошибки: '. $errorCode);
            }

            $order = (new OrderSearchOperation($this->api))->orderNumber($item['orderno'])->search()[0] ?? null;

            if (!$order) {
                throw new MeasoftException('Не удалось получить заказ');
            }

            return $order;
        }

        throw new MeasoftException('Неизвестная ошибка');
    }
}
