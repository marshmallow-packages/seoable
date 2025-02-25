<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

class SchemaNutritionInformation extends Schema
{
    protected $calories;
    protected $cholesterolContent;
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

    public function calories(float $amount = null)
    {
        return $this->addMass('calories', $amount, 'calories');
    }

    public function carbohydrate(float $amount = null)
    {
        return $this->addMass('carbohydrateContent', $amount);
    }

    public function cholesterol(float $amount = null)
    {
        return $this->addMass('cholesterolContent', $amount, 'milligrams');
    }

    public function fat(float $amount = null)
    {
        return $this->addMass('fatContent', $amount);
    }

    public function fiber(float $amount = null)
    {
        return $this->addMass('fiberContent', $amount);
    }

    public function protein(float $amount = null)
    {
        return $this->addMass('proteinContent', $amount);
    }

    public function saturatedFat(float $amount = null)
    {
        return $this->addMass('saturatedFatContent', $amount);
    }

    public function servingSize($serving_size = null)
    {
        if ($serving_size) {
            $this->servingSize = $serving_size;
        }

        return $this;
    }

    public function sodium(float $amount = null)
    {
        return $this->addMass('sodiumContent', $amount, 'milligrams');
    }

    public function sugar(float $amount = null)
    {
        return $this->addMass('sugarContent', $amount);
    }

    public function transFat(float $amount = null)
    {
        return $this->addMass('transFatContent', $amount);
    }

    public function unsaturatedFat(float $amount = null)
    {
        return $this->addMass('unsaturatedFatContent', $amount);
    }

    protected function addMass(string $column, float $amount = null, string $mass = 'grams')
    {
        if ($amount) {
            $this->{$column} = "$amount $mass";
        }

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
