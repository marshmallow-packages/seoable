<?php

namespace Marshmallow\Seoable\Helpers\Schemas;

class SchemaFaqPage extends Schema
{
    protected $questions = [];

    public static function make()
    {
        $schema = new self();

        return $schema;
    }

    public function addQuestionAndAnswer($question = null, $answer = null)
    {
        if (!$question || !$answer) {
            return $this;
        }

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
