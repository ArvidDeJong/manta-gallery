<?php

namespace Darvis\MantaGallery\Traits;

use Darvis\MantaGallery\Models\Gallery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;
use Manta\FluxCMS\Services\ModuleSettingsService;

trait GalleryTrait
{
    public function __construct()
    {
        $this->module_routes = [
            'name' => 'gallery',
            'list' => 'gallery.list',
            'create' => 'gallery.create',
            'update' => 'gallery.update',
            'read' => 'gallery.read',
            'upload' => 'gallery.upload',
            'settings' => null,
            'maps' => null,
        ];

        $settings = ModuleSettingsService::ensureModuleSettings('gallery', "darvis/manta-gallery");
        $this->config = $settings;

        $this->fields = $settings['fields'] ?? [];
        $this->tab_title = 'title'; // $settings['tab_title'] ?? 'title';
        $this->moduleClass = 'Darvis\MantaGallery\Models\Gallery';
    }


    // * Model items
    public ?Gallery $item = null;
    public ?Gallery $itemOrg = null;



    #[Locked]
    public ?string $company_id = null;

    #[Locked]
    public ?string $host = null;

    public ?string $locale = null;
    public ?string $pid = null;

    public ?string $title = null;
    public ?string $title_2 = null;
    public ?string $title_3 = null;
    public ?string $slug = null;
    public ?string $seo_title = null;
    public ?string $seo_description = null;
    public ?string $tags = null;
    public ?string $summary = null;
    public ?string $excerpt = null;
    public ?string $content = null;
    public ?string $homepage = null;
    public ?string $locked = null;
    public ?string $fullpage = null;


    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where(function (Builder $querysub) {
                $querysub->where('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('content', 'LIKE', "%{$this->search}%");
            });
    }

    public function rules()
    {
        $return = [];
        if ($this->fields['title']) $return['title'] = 'required';
        // if ($this->fields['excerpt']) $return['excerpt'] = 'required';
        return $return;
    }

    public function messages()
    {
        $return = [];
        if ($this->fields['title']) $return['title.required'] = 'De titel is verplicht';
        if ($this->fields['excerpt']) $return['excerpt.required'] = 'De inleiding is verplicht';
        return $return;
    }
}
