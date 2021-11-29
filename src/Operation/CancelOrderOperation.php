<?php

namespace Measoft\Operation;

use Measoft\MeasoftException;
use SimpleXMLElement;

class CancelOrderOperation extends AbstractOperation
{
    /** @var string $orderCode Отменить заказ по внутреннему коду заказа */
    private $orderCode;

    /** @var string $orderNumber Отменить заказ по номеру заказа */
    private $orderNumber;

    /**
     * @param string $orderCode Отменить заказ по внутреннему коду заказа
     * @return self
     */
    public function orderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    /**
     * @param string $orderNumber Отменить заказ по номеру заказа
     * @return self
     */
    public function orderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Сформировать XML
     *
     * @return SimpleXMLElement
     */
    private function buildXml(): SimpleXMLElement
    {
        $xml   = $this->createXml('cancelorder');
        $order = $xml->addChild('order');

        $order->addAttribute('orderno', $this->orderNumber);
        $order->addAttribute('ordercode', $this->orderCode);

        return $xml;
    }

    /**
     * Отменить заказ
     *
     * @return void
     * @throws MeasoftException
     */
    public function cancel()
    {
        $response = $this->request($this->buildXml());

        if (!$response->isSuccess()) {
            throw new MeasoftException($response->getError());
        }

        $resultXml = $response->getXml();

        foreach ($resultXml as $item) {
            $errorCode = intval($item['error'] ?? 0);

            if ($errorCode) {
                throw new MeasoftException($item['errormsgru'] ?? $item['errormsg'] ?? 'Код ошибки: '. $errorCode);
            }

            return;
        }

        throw new MeasoftException('Неизвестная ошибка');
    }
}