<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

class SchemaNutritionInformation extends Schema
{
    protected $calories;
    protected $carbohydrateContent;
    protected $fatContent;
    protected $fiberContent;
    protected $proteinContent;
    protected $saturatedFatContent;
    protected $servingSize;
    protected $sodiumContent;
    protected $sugarContent;
    protected $transFatContent;
    protected $unsaturatedFatContent;

    public static function make()
    {
        $schema = new self();

        return $schema;
    }

    public function calories(float $amount)
    {
        return $this->addMass('calories', $amount, 'calories');
    }

    public function carbohydrate(float $amount)
    {
        return $this->addMass('carbohydrateContent', $amount);
    }

    public function cholesterol(float $amount)
    {
        return $this->addMass('cholesterolContent', $amount, 'milligrams');
    }

    public function fat(float $amount)
    {
        return $this->addMass('fatContent', $amount);
    }

    public function fiber(float $amount)
    {
        return $this->addMass('fiberContent', $amount);
    }

    public function protein(float $amount)
    {
        return $this->addMass('proteinContent', $amount);
    }

    public function saturatedFat(float $amount)
    {
        return $this->addMass('saturatedFatContent', $amount);
    }

    public function servingSize($servingSize)
    {
        $this->servingSize = $servingSize;

        return $this;
    }

    public function sodium(float $amount)
    {
        return $this->addMass('sodiumContent', $amount, 'milligrams');
    }

    public function sugar(float $amount)
    {
        return $this->addMass('sugarContent', $amount);
    }

    public function transFat(float $amount)
    {
        return $this->addMass('transFatContent', $amount);
    }

    public function unsaturatedFat(float $amount)
    {
        return $this->addMass('unsaturatedFatContent', $amount);
    }

    protected function addMass(string $column, float $amount, string $mass = 'grams')
    {
        $this->{$column} = "$amount $mass";

        return $this;
    }

    public function toArray()
    {
        return [
            '@type' => 'NutritionInformation',
            'calories' => $this->calories,
            'carbohydrateContent' => $this->carbohydrateContent,
            'cholesterolContent' => $this->cholesterolContent,
            'fatContent' => $this->fatContent,
            'fiberContent' => $this->fiberContent,
            'proteinContent' => $this->proteinContent,
            'saturatedFatContent' => $this->saturatedFatContent,
            'servingSize' => $this->servingSize,
            'sodiumContent' => $this->sodiumContent,
            'sugarContent' => $this->sugarContent,
            'transFatContent' => $this->transFatContent,
            'unsaturatedFatContent' => $this->unsaturatedFatContent,
        ];
    }
}
