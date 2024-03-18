<?php

namespace Marshmallow\Seoable;

use Exception;
use Illuminate\Support\Str;
use Marshmallow\TagsField\Tags;
use Illuminate\Support\Stringable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Marshmallow\Seoable\Traits\Seoable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\Seoable\Helpers\Schemas\Schema;
use Marshmallow\Nova\Flexible\Layouts\Collection;
use Marshmallow\Seoable\Helpers\Schemas\SchemaListItem;
use Marshmallow\Seoable\Helpers\Schemas\SchemaOrganization;
use Marshmallow\Seoable\Helpers\Schemas\SchemaBreadcrumbList;

class Seo
{
    protected $model;
    protected $title;
    protected $description;
    protected $keywords;
    protected $image;
    protected $canonical;
    protected $follow_type;
    protected $hide_in_sitemap;
    protected $schemas;
    protected $breadcrumbs;
    protected $page_type;
    protected $seoable_content;
    protected $href_langs = [];

    protected $model_is_set = false;

    public static $routeModel = \Marshmallow\Seoable\Models\Route::class;
    public static $prettyUrlModel = \Marshmallow\Seoable\Models\PrettyUrl::class;
    public static $seoableItemModel = \Marshmallow\Seoable\Models\SeoableItem::class;
    public static $prettyUrlResolver = \Marshmallow\Seoable\Helpers\PrettyUrlResolver::class;
    public static $routeLocale;

    protected $manual_values = [];

    public function __construct()
    {
        if (!self::$routeLocale) {
            self::$routeLocale = config('app.locale');
        }

        if (config('seo.defaults.logo')) {
            $company_schema = SchemaOrganization::make(config('seo.defaults.logo'));
            $this->addSchema($company_schema);
        }
    }

    public function set($model, $fix_this_model = false)
    {
        if ($this->model_is_set) {
            return $this;
        }

        if ($fix_this_model === true) {
            $this->model_is_set = true;
        }

        if ($model instanceof Model) {
            $this->setFromModel($model);
        }

        return $this;
    }

    public function addSchema(Schema $schema)
    {
        $this->schemas[] = $schema;
    }

    public function addSchemas(array $schemas)
    {
        foreach ($schemas as $schema) {
            $this->addSchema($schema);
        }
    }

    public function addBreadcrumb(SchemaListItem $breadcrumb)
    {
        $this->breadcrumbs[] = $breadcrumb;
    }

    public function getSchema()
    {
        $schema_output = [];

        if ($this->schemas) {
            foreach ($this->schemas as $schema) {
                $schema_output[] = $schema->toJson();
            }
        }

        if ($this->breadcrumbs) {
            $breadcrumb_list = SchemaBreadcrumbList::make();
            foreach ($this->breadcrumbs as $breadcrumb) {
                $breadcrumb_list->addItem($breadcrumb);
            }
            $schema_output[] = $breadcrumb_list->toJson();
        }

        return json_encode($schema_output, JSON_UNESCAPED_SLASHES);
    }

    public function hasSchema()
    {
        return $this->schemas || $this->breadcrumbs;
    }

    protected function isTheDefaultSeoValue($value, $database_column)
    {
        $default_value = $this->getDefaultValue($database_column);

        return $value == $default_value;
    }

    protected function shouldStoreValues($value, $database_column)
    {
        if ($this->model && $this->model->shouldStoreRecordOnInsert()) {
            return true;
        }

        if ($this->isTheDefaultSeoValue($value, $database_column)) {
            return false;
        }

        return true;
    }

    protected function shouldNotStoreValues($value, $database_column)
    {
        return !$this->shouldStoreValues($value, $database_column);
    }

    public function storeKeywordsColumn($value)
    {
        if (is_array($value)) {
            return $value;
        }

        return array_filter(
            explode(Tags::EXPLODE_BY, $value)
        );
    }

    public function store(NovaRequest $request, $request_param, $database_column)
    {
        /**
         * Value to be stored in the database.
         */
        $value = $request->{$request_param};

        /**
         * Modify the base content
         */
        $method  = 'store' . Str::studly($database_column) . 'Column';
        if (method_exists($this, $method)) {
            $value = $this->{$method}($value);
        }

        if ($this->shouldNotStoreValues($value, $database_column)) {
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

        /*
         * Check if the connected image is still available.
         * If not, we set the value to null.
         */

        if ($seoable = $this->model->fresh()->seoable) {
            if (!$seoable->image || ($seoable->image && Storage::disk(config('seo.storage.disk'))->missing($seoable->image))) {
                $seoable->update([
                    'image' => null,
                ]);
            }

            if ($seoable->isEmpty()) {
                $seoable->delete();
            }
        }
    }

    protected function getDefaultValue($database_column)
    {
        $method_name = 'getDefaultSeo' . Str::of($database_column)->camel()->ucfirst();

        return $this->$method_name();
    }

    public function setFromModel(Model $model)
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
        $this->hide_in_sitemap = $model->setHideInSitemap();
    }

    protected function hasSeoableValue($field)
    {
        if (array_key_exists($field, $this->manual_values)) {
            return $this->manual_values[$field];
        }

        if (!$this->model) {
            return false;
        }

        if (!$this->model->seoable) {
            return false;
        }

        if (!$this->model->seoable->{$field}) {
            return false;
        }

        return $this->model->seoable->{$field};
    }

    protected function getDefault($column)
    {
        if (!$this->{$column}) {
            return config('seo.defaults.' . $column);
        }

        return $this->{$column};
    }

    public function setTitle(string $title)
    {
        $this->manual_values['title'] = $title;
        return $this;
    }

    public function setDescription(string $description)
    {
        $this->manual_values['description'] = $description;
        return $this;
    }

    public function setKeywords(array $keywords)
    {
        $this->manual_values['keywords'] = $keywords;
        return $this;
    }

    public function setHrefs(array $hrefs)
    {
        $this->href_langs = array_merge($hrefs, $this->href_langs);
        return $this;
    }

    public function setImage(string $image)
    {
        $this->manual_values['image'] = $image;
        return $this;
    }

    public function setFollowType(string $follow_type)
    {
        $this->manual_values['follow_type'] = $follow_type;
        return $this;
    }

    public function setHideInSitemap(bool $hide_in_sitemap)
    {
        $this->manual_values['hide_in_sitemap'] = $hide_in_sitemap;
        return $this;
    }

    public function setLocale(string $locale)
    {
        $this->manual_values['locale'] = $locale;
        return $this;
    }

    public function setHtmlLanguage(string $html_language)
    {
        $this->manual_values['html_language'] = $html_language;
        return $this;
    }

    protected function getDefaultSeoTitle()
    {
        return $this->getDefault('title');
    }

    protected function getDefaultSeoDescription()
    {
        return $this->getDefault('description');
    }

    protected function getDefaultSeoKeywords()
    {
        return $this->getDefault('keywords');
    }

    protected function getDefaultSeoFollowType()
    {
        return $this->getDefault('follow_type');
    }

    protected function getDefaultSeoHideInSitemap()
    {
        return $this->getDefault('hide_in_sitemap');
    }

    protected function getDefaultSeoImage()
    {
        return $this->getDefault('image');
    }

    protected function getDefaultSeoPageType()
    {
        return $this->getDefault('page_type');
    }

    public function getSeoTitle()
    {
        if ($title = $this->hasSeoableValue('title')) {
            return $title;
        }

        return $this->getDefaultSeoTitle();
    }

    public function getSeoDescription()
    {
        if ($description = $this->hasSeoableValue('description')) {
            return strip_tags($description);
        }

        if (!$this->description) {
            return strip_tags(config('seo.defaults.description'));
        }

        return strip_tags($this->description);
    }

    public function getSeoKeywords()
    {
        if ($keywords = $this->hasSeoableValue('keywords')) {
            return $keywords;
        }

        if (!$this->keywords || empty($this->keywords)) {
            return config('seo.defaults.keywords');
        }

        return $this->keywords;
    }

    public function getSeoKeywordsAsString()
    {
        if (!$this->getSeoKeywords() || !is_array($this->getSeoKeywords())) {
            return null;
        }

        return join(',', $this->getSeoKeywords());
    }

    public function getSeoImage()
    {
        if ($image = $this->hasSeoableValue('image')) {
            return $image;
        }

        if (!$this->image) {
            return config('seo.defaults.image');
        }

        return $this->image;
    }

    public function getSeoPageType()
    {
        if ($page_type = $this->hasSeoableValue('page_type')) {
            return $page_type;
        }

        if (!$this->page_type || empty($this->page_type)) {
            return config('seo.defaults.page_type');
        }

        return $this->page_type;
    }

    public function getHideInSitemap()
    {
        if ($hide_in_sitemap = $this->hasSeoableValue('hide_in_sitemap')) {
            return $hide_in_sitemap;
        }
        return $this->hide_in_sitemap;
    }

    public function getSeoLocale()
    {
        if (array_key_exists('locale', $this->manual_values)) {
            return $this->manual_values['locale'];
        }

        $locale = app()->getLocale();
        if (false === strpos($locale, '_')) {
            $locale .= '_' . Str::upper(app()->getLocale());
        }

        return $locale;
    }

    public function getHtmlLanguage()
    {
        if (array_key_exists('html_language', $this->manual_values)) {
            return $this->manual_values['html_language'];
        }

        return config('app.locale');
    }

    public function getSeoImageUrl()
    {
        if ($image = $this->hasSeoableValue('image')) {
            if (Str::of($image)->startsWith('http')) {
                return $image;
            }
            return Storage::disk('public')->url($image);
        }

        return $this->getDefaultSeoImage();
    }

    public function setSeoCanonicalUrl(string $canonical)
    {
        $this->canonical = $canonical;
        return $this;
    }

    public function getSeoCanonicalUrl()
    {
        return $this->canonical ?? request()->url();
    }

    public function getHrefLang()
    {
        return $this->href_langs;
    }

    public function setSeoableContent(Collection $seoable_content)
    {
        $this->seoable_content = $seoable_content;
        return $this;
    }

    public function getSeoFollowType()
    {
        if ($follow_type = $this->hasSeoableValue('follow_type')) {
            return $follow_type;
        }

        if (!$this->follow_type) {
            return config('seo.defaults.follow_type');
        }

        return $this->follow_type;
    }

    public function googleOptimize()
    {
        $via = null;
        if (config('seo.google.GTM') && config('seo.google_optimize.via_gtm')) {
            $via = 'GTM';
            $container = config('seo.google.GTM');
        } elseif (config('seo.google_optimize.container')) {
            $via = 'container';
            $container = config('seo.google_optimize.container');
        }

        if ($via) {
            return view('seoable::google.optimize')->with([
                'via' => $via,
                'container' => $container,
            ]);
        }
    }

    public function addGtagFunction(): bool
    {
        return config('seo.google.gtag_function') && !config('seo.google.GA');
    }

    public function googleTagManagerId()
    {
        $gtmId = config('seo.google.tagmanager.id');
        if (!$gtmId) {
            $gtmId = config('seo.google.GTM');
        }
        return $gtmId;
    }

    public function googleTagManagerEnabled(): bool
    {
        if (!$this->googleTagManagerId()) {
            return false;
        }

        $enabled = config('seo.google.tagmanager.enabled');
        if (is_null($enabled)) {
            return true;
        }

        return $enabled;
    }

    public function googleTagManagerUrlSuffix(): string
    {
        $gtmEnv = config('seo.google.tagmanager.env');
        $gtmAuth = config('seo.google.tagmanager.auth');

        if (!$this->googleTagManagerEnabled() || !$gtmEnv) {
            return '';
        }

        return Str::of("&gtm_preview={$gtmEnv}")
            ->when($gtmAuth, function (Stringable $string) use ($gtmAuth) {
                return $string->append("&gtm_auth={$gtmAuth}");
            })->append("&gtm_cookies_win=x")->toHtmlString();
    }

    public function content(string $type, $column = 'content')
    {
        if (!$this->seoable_content) {
            return null;
        }

        foreach ($this->seoable_content as $content) {
            if ($content->type == $type) {
                return $content->{$column};
            }
        }

        return null;
    }

    public function generate()
    {
        return view('seoable::seo');
    }

    public function generateBody()
    {
        return view('seoable::seo_body');
    }
}
