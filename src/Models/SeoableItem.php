<?php

namespace Marshmallow\Seoable\Models;

use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Translatable\Traits\Translatable;

class SeoableItem extends Model
{
	use Translatable;

    /**
     * Guarded variables
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Hidden variables
     *
     * @var array
     */
    protected $hidden = [
        'seoable_type', 'created_at', 'updated_at'
    ];

    /**
     * Table name for the model
     *
     * @var string
     */
    protected $table = 'seoable';

    /**
     * Casts variables
     *
     * @var array
     */
    protected $casts = [
        // 'params' => 'object',
        'keywords' => 'array',
    ];

    public function isEmpty ()
    {
        $empty_check = [
            'title',
            'description',
            'keywords',
            'follow_type',
            'image',
        ];

        foreach ($empty_check as $column) {
            if ($this->{$column} !== null) {
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
