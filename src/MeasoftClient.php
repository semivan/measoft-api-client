<?php

namespace Measoft;

use Measoft\Object\Country;
use Measoft\Operation\CalculatorOperation;
use Measoft\Operation\CancelOrderOperation;
use Measoft\Operation\CommitLastStatusOperation;
use Measoft\Operation\ItemSearchOperation;
use Measoft\Operation\CreateOrderOperation;
use Measoft\Operation\OrderSearchOperation;
use Measoft\Operation\PvzSearchOperation;
use Measoft\Operation\RegionSearchOperation;
use Measoft\Operation\StreetSearchOperation;
use Measoft\Operation\TownSearchOperation;

class MeasoftClient
{
    /** @var Api $api */
    private $api;

    /**
     * @param string $login     Логин
     * @param string $password  Пароль
     * @param string $extracode Код службы
     */
    public function __construct(string $login, string $password, string $extracode)
    {
        $this->api = new Api($login, $password, $extracode);
    }

    /**
     * Получить список стран
     *
     * @return Country[]
     * @throws MeasoftException
     */
    public function getCountryList(): array
    {
        foreach ($this->regionSearch()->search() as $region) {
            $countries[$region->getCountry()->getCode()] = $region->getCountry();
        }

        return $countries ?? [];
    }
    
    /** Поиск региона */
    public function regionSearch(): RegionSearchOperation
    {
        return new RegionSearchOperation($this->api);
    }
    
    /** Поиск города */
    public function townSearch(): TownSearchOperation
    {
        return new TownSearchOperation($this->api);
    }
    
    /** Поиск улицы */
    public function streetSearch(): StreetSearchOperation
    {
        return new StreetSearchOperation($this->api);
    }
    
    /** Поиск пункта самовывоза */
    public function pvzSearch(): PvzSearchOperation
    {
        return new PvzSearchOperation($this->api);
    }
    
    /** Поиск номенклатуры */
    public function itemSearch(): ItemSearchOperation
    {
        return new ItemSearchOperation($this->api);
    }

    /** Расчет стоимости доставки согласно тарифам КС */
    public function calculator(): CalculatorOperation
    {
        return new CalculatorOperation($this->api);
    }

    /** Поиск заказа */
    public function orderSearch(): OrderSearchOperation
    {
        return new OrderSearchOperation($this->api);
    }

    /** Создание заказа */
    public function createOrder(): CreateOrderOperation
    {
        return new CreateOrderOperation($this->api);
    }

    /** Отмена заказа */
    public function cancelOrder(): CancelOrderOperation
    {
        return new CancelOrderOperation($this->api);
    }

    public function commitLastStatus(): CommitLastStatusOperation
    {
        return new CommitLastStatusOperation($this->api);
    }
}
