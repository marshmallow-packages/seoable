<?php

namespace Marshmallow\Seoable;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Marshmallow\Seoable\Traits\Seoable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\Seoable\Helper\Schema\Schema;

class Seo
{
	protected $model;
	protected $title;
	protected $description;
	protected $keywords;
	protected $image;
	protected $follow_type;
	protected $schemas;

	public function set ($model)
	{
		if ($model instanceof Model) {
			$this->setFromModel($model);
		}

		return $this;
	}

	public function addSchema (Schema $schema)
	{
		$this->schemas[] = $schema;
	}

	public function addSchemas (array $schemas)
	{
		foreach ($schemas as $schema) {
			$this->addSchema($schema);
		}
	}

	public function getSchema ()
	{
		$schema_output = [];
		foreach ($this->schemas as $schema) {
			$schema_output[] = $schema->toJson();
		}
		return json_encode($schema_output);
	}

	public function hasSchema ()
	{
		return ($this->schemas);
	}

	protected function isTheDefaultSeoValue ($value, $database_column)
	{
		$default_value = $this->getDefaultValue($database_column);
		return ($value == $default_value);
	}

	public function store (NovaRequest $request, $request_param, $database_column)
	{
		/**
		 * Value to be stored in the database
		 */
		$value = $request->{$request_param};

		if ($this->isTheDefaultSeoValue($value, $database_column)) {
			/**
			 * Don't this data in the database if it's not manualy adjusted.
			 */
			$value = null;
		}

		$data = [
			$database_column => $value,
		];

		$model = $this->model->fresh();

		if (!$model->seoable) {
			$model->seoable()->create($data);
		} else {
			$model->seoable()->update($data);			
		}

		/**
		 * Check if the connected image is still available.
		 * If not, we set the value to null.
		 */

		if ($seoable = $this->model->fresh()->seoable) {

			if (!Storage::disk(config('seo.storage.disk'))->exists($seoable->image)) {
				$seoable->update([
					'image' => null,
				]);
			}

			if ($seoable->isEmpty()) {
				$seoable->delete();
			}
		}

	}

	protected function getDefaultValue ($database_column)
	{
		$method_name = 'getDefaultSeo' . Str::of($database_column)->camel()->ucfirst();
		return $this->$method_name();
	}

	public function setFromModel (Model $model)
	{
		if (!in_array(Seoable::class, class_uses($model))) {
			throw new Exception(get_class($model) . ' should implement ' . Seoable::class);
		}

		$this->model = $model;
		$this->title = $model->setSeoTitle();
		$this->description = $model->setSeoDescription();
		$this->keywords = $model->setSeoKeywords();
		$this->image = $model->setSeoImage();
		$this->follow_type = $model->setSeoFollowType();
	}

	protected function hasSeoableValue ($field)
	{
		if (!$this->model->seoable) {
			return false;
		}

		if (!$this->model->seoable->{$field}) {
			return false;
		}

		return $this->model->seoable->{$field};
	}

	protected function getDefault ($column)
	{
		if (!$this->{$column}) {
			return config('seo.defaults.' . $column);
		}

		return $this->{$column};
	}

	protected function getDefaultSeoTitle ()
	{
		return $this->getDefault('title');
	}

	protected function getDefaultSeoDescription ()
	{
		return $this->getDefault('description');
	}

	protected function getDefaultSeoKeywords ()
	{
		return $this->getDefault('keywords');
	}

	protected function getDefaultSeoFollowType ()
	{
		return $this->getDefault('follow_type');
	}

	protected function getDefaultSeoImage ()
	{
		return $this->getDefault('image');
	}

	public function getSeoTitle ()
	{
		if ($title = $this->hasSeoableValue('title')) {
			return $title;
		}
		return $this->getDefaultSeoTitle();
	}

	public function getSeoDescription ()
	{
		if ($description = $this->hasSeoableValue('description')) {
			return $description;
		}

		if (!$this->description) {
			return config('seo.defaults.description');
		}

		return $this->description;
	}

	public function getSeoKeywords ()
	{
		if ($keywords = $this->hasSeoableValue('keywords')) {
			return $keywords;
		}

		if (!$this->keywords || empty($this->keywords)) {
			return config('seo.defaults.keywords');
		}

		return $this->keywords;
	}

	public function getSeoKeywordsAsString ()
	{
		return join(',', $this->getSeoKeywords());
	}

	public function getSeoImage ()
	{
		if ($image = $this->hasSeoableValue('image')) {
			return $image;
		}

		if (!$this->image) {
			return config('seo.defaults.image');
		}

		return $this->image;
	}

	public function getSeoImageUrl ()
	{
		if ($image = $this->hasSeoableValue('image')) {
			return Storage::disk('public')->url($image);
		}

		return $this->getDefaultSeoImage();
	}

	public function getSeoFollowType ()
	{
		if ($follow_type = $this->hasSeoableValue('follow_type')) {
			return $follow_type;
		}

		if (!$this->follow_type) {
			return config('seo.defaults.follow_type');
		}

		return $this->follow_type;
	}

	public function generate ()
	{
		return view('seoable::seo');
	}
}
