<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Priceable\Models\Price;

class SchemaOffer extends Schema
{
    const IN_STOCK = 'InStock';
    const DISCONTINNUES = 'Discontinued';
    const IN_STORE_ONLY = 'InStoreOnly';
    const LIMITED_AVAILABILITY = 'LimitedAvailability';
    const ONLINE_ONLY = 'OnlineOnly';
    const OUT_OF_STOCK = 'OutOfStock';
    const PRE_ORDER = 'PreOrder';
    const PRE_SALE = 'PreSale';
    const SOLD_OUT = 'SoldOut';

    const CONDITION_DAMAGED = 'DamagedCondition';
    const CONDITION_NEW = 'NewCondition';
    const CONDITION_REFURBISHED = 'RefurbishedCondition';
    const CONDITION_USED = 'UsedCondition';

    protected $url;

    protected $price;

    protected $priceCurrency;

    protected $priceValidUntil;

    protected $availability = self::IN_STOCK;

    protected $itemCondition = self::CONDITION_NEW;

    public static function make(Price $price)
    {
        $schema = new self();
        $schema->price = $price->price();
        $schema->priceCurrency = $price->currency->iso_4217;

        if ($price->valid_till) {
            $schema->priceValidUntil = $price->valid_till->format('Y-m-d');
        }

        return $schema;
    }

    public function availability($availability = null)
    {
        if ($availability) {
            $this->availability = $availability;
        }

        return $this;
    }

    public function itemCondition($itemCondition = null)
    {
        if ($itemCondition) {
            $this->itemCondition = $itemCondition;
        }

        return $this;
    }

    public function url(string $url = null): self
    {
        if ($url) {
            $this->url = $url;
        }

        return $this;
    }

    public function toArray()
    {
        return [
            '@type' => 'Offer',
            'url' => $this->url,
            'availability' => 'http://schema.org/' . $this->availability,
            'itemCondition' => 'http://schema.org/' . $this->itemCondition,
            'price' => $this->price,
            'priceValidUntil' => $this->priceValidUntil,
            'priceCurrency' => $this->priceCurrency,
        ];
    }
}
