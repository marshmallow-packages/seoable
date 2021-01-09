<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;
use Marshmallow\Seoable\Helpers\Schemas\SchemaNutritionInformation;

class SchemaRecipe extends Schema
{
    use Makeable;

    protected $person;

    protected $images;

    protected $author;

    protected $nutrition;

    protected $text;

    protected $datePublished;

    protected $description;

    protected $prepTime;

    protected $cookTime;

    protected $totalTime;

    protected $keywords;

    protected $recipeYield;

    protected $recipeCategory;

    protected $recipeCuisine;

    protected $recipeIngredient;

    protected $aggregateRating;

    protected $recipeInstructions = [];

    public function author($name)
    {
        $this->person = SchemaPerson::make($name);
        return $this;
    }

    public function prepTime(int $minutes)
    {
        return $this->setTimeUnit('prepTime', $minutes);
    }

    public function cookTime(int $minutes)
    {
        return $this->setTimeUnit('cookTime', $minutes);
    }

    public function totalTime(int $minutes)
    {
        return $this->setTimeUnit('totalTime', $minutes);
    }

    public function keywords(array $keywords)
    {
        $this->keywords = join(', ', $keywords);
        return $this;
    }

    public function recipeYield(int $recipeYield)
    {
        $this->recipeYield = $recipeYield;
        return $this;
    }

    public function recipeCategory(string $recipeCategory)
    {
        $this->recipeCategory = $recipeCategory;
        return $this;
    }

    public function recipeCuisine(string $recipeCuisine)
    {
        $this->recipeCuisine = $recipeCuisine;
        return $this;
    }

    public function nutrition(SchemaNutritionInformation $nutrition)
    {
        $this->nutrition = $nutrition;
        return $this;
    }

    public function ingredients(array $ingredients)
    {
        $this->recipeIngredient = $ingredients;
        return $this;
    }

    public function instructions(SchemaHowToStep ...$instructions)
    {
        foreach ($instructions as $instruction) {
            $this->recipeInstructions[] = $instruction->toJson();
        }
        return $this;
    }

    protected function setTimeUnit(string $column, int $minutes)
    {
        $this->{$column} = "PT{$minutes}M";
        return $this;
    }

    public function toArray()
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Recipe',
            'name' => $this->name,
            'image' => $this->images,
            'author' => $this->getJsonSchema('person'),
            'datePublished' => $this->datePublished,
            'description' => $this->description,
            'prepTime' => $this->prepTime,
            'cookTime' => $this->cookTime,
            'totalTime' => $this->totalTime,
            'keywords' => $this->keywords,
            'recipeYield' => $this->recipeYield,
            'recipeCategory' => $this->recipeCategory,
            'recipeCuisine' => $this->recipeCuisine,
            'nutrition' => $this->getJsonSchema('nutrition'),
            'aggregateRating' => $this->aggregateRating,
            'recipeIngredient' => $this->recipeIngredient,
            'recipeInstructions' => $this->recipeInstructions,
            'video' => $this->getJsonSchema('video'),
        ];
    }
}
