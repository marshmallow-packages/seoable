<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Illuminate\Support\Str;
use Money\Money;

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

    protected $availability = self::IN_STOCK;

    public static function make(Money $price)
    {
        $schema = new self();
        $schema->price = $price;

        return $schema;
    }

    public function availability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    public function toJson()
    {
        return [
            '@type' => 'Offer',
            'availability' => 'http://schema.org/'.$this->availability,
            'price' => $this->price,
            'priceCurrency' => Str::of(env('CASHIER_CURRENCY'))->upper(),
        ];
    }
}
