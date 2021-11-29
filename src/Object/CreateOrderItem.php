<?php

namespace Measoft\Object;

class CreateOrderItem
{
    /** @var string $article Артикул */
    private $article;

    /** @var string $barcode Штрих-код производителя */
    private $barcode;

    /** @var string $name Наименование */
    private $name;

    /** @var float $retailPrice Розничная цена по-умолчанию. При оформлении заказа цена используется та, которая указана в заказе. */
    private $retailPrice;

    /** @var float $weight Масса в килограммах */
    private $weight;

    /** @var float $length Длина в сантиметрах */
    private $length;

    /** @var float $width Ширина в сантиметрах */
    private $width;

    /** @var float $height Высота в сантиметрах */
    private $height;

    /** @var int $quantity Количество на складе */
    private $quantity;

    /** @var string $externalCode Внешний код строки */
    private $externalCode;

    /** @var int $vatRate Ставка НДС */
    private $vatRate;

    /** @var float $volume Объемный вес единицы товара в килограммах */
    private $volume;

    /**
     * @var int $type Тип вложения
     * 1 - Товар
     * 2 - Доставка (Такое вложение добавится автоматически, если заполнить order->deliveryprice)
     * 3 - Услуга
     * 4 - Предоплата (сумма)
     * 6 - Оплата кредитом (сумма)
     */
    private $type;

    /** @return string|null Артикул */
    public function getArticle(): ?string
    {
        return $this->article;
    }

    /**
     * @param  string $article Артикул
     * @return self
     */
    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    /** @return string|null Штрих-код производителя */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode Штрих-код производителя
     * @return self
     */
    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /** @return string|null Наименование */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name Наименование
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @return float|null Розничная цена по-умолчанию. При оформлении заказа цена используется та, которая указана в заказе. */
    public function getRetailPrice(): ?float
    {
        return $this->retailPrice;
    }

    /**
     * @param float $retailPrice Розничная цена по-умолчанию. При оформлении заказа цена используется та, которая указана в заказе.
     * @return self
     */
    public function setRetailPrice(float $retailPrice): self
    {
        $this->retailPrice = $retailPrice;

        return $this;
    }

    /** @return float|null Масса в килограммах */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param float $weight Масса в килограммах
     * @return self
     */
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /** @return float|null Длина в сантиметрах */
    public function getLength(): ?float
    {
        return $this->length;
    }

    /**
     * @param float $length Длина в сантиметрах
     * @return self
     */
    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    /** @return float|null Ширина в сантиметрах */
    public function getWidth(): ?float
    {
        return $this->width;
    }

    /**
     * @param float $width Ширина в сантиметрах
     * @return self
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    /** @return float|null Высота в сантиметрах */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     * @param float $height Высота в сантиметрах
     * @return self
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    /** @return int|null Количество на складе */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity Количество на складе
     * @return self
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /** @return string|null Внешний код строки */
    public function getExternalCode(): ?string
    {
        return $this->externalCode;
    }

    /**
     * @param string $externalCode Внешний код строки
     * @return self
     */
    public function setExternalCode(string $externalCode): self
    {
        $this->externalCode = $externalCode;

        return $this;
    }

    /** @return int|null Ставка НДС */
    public function getVatRate(): ?int
    {
        return $this->vatRate;
    }

    /**
     * @param int $vatRate Ставка НДС
     * @return self
     */
    public function setVatRate(int $vatRate): self
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    /** @return float|null Объемный вес единицы товара в килограммах */
    public function getVolume(): ?float
    {
        return $this->volume;
    }

    /**
     * @param float $volume Объемный вес единицы товара в килограммах
     * @return self
     */
    public function setVolume(float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    /** @return int|null Тип вложения */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type Тип вложения
     * 1 - Товар
     * 2 - Доставка (Такое вложение добавится автоматически, если заполнить order->deliveryprice)
     * 3 - Услуга
     * 4 - Предоплата (сумма)
     * 6 - Оплата кредитом (сумма)
     * @return self
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
