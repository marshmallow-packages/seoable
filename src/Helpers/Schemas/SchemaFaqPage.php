<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

use Marshmallow\Seoable\Helpers\Schemas\SchemaAnswer;
use Marshmallow\Seoable\Helpers\Schemas\Traits\Makeable;

class SchemaFaqPage extends Schema
{
    protected $questions = [];

    public static function make()
    {
        $schema = new self();
        return $schema;
    }

    public function addQuestionAndAnswer($question, $answer)
    {
        $question = SchemaQuestion::make($question);
        $question->addAnswer(
            SchemaAnswer::make($answer)
        );

        $this->questions[] = $question->toArray();
        return $this;
    }

    public function toArray()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $this->questions,
        ];
    }
}
