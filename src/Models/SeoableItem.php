<?php

namespace Marshmallow\Seoable\Models;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\Translatable\Traits\Translatable;

class SeoableItem extends Model
{
    use Translatable;

    /**
     * Guarded variables.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Hidden variables.
     *
     * @var array
     */
    protected $hidden = [
        'seoable_type', 'created_at', 'updated_at',
    ];

    /**
     * Table name for the model.
     *
     * @var string
     */
    protected $table = 'seoable';

    /**
     * Casts variables.
     *
     * @var array
     */
    protected $casts = [
        'keywords' => 'array',
    ];

    public function isEmpty()
    {
        $empty_check = [
            'title',
            'description',
            'keywords',
            'follow_type',
            'image',
        ];

        foreach ($empty_check as $column) {
            if (null !== $this->{$column}) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the owning seo_metaable model.
     *
     * @return morphTo
     */
    public function seoable()
    {
        return $this->morphTo();
    }
}
