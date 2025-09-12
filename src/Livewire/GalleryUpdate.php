<?php

namespace Darvis\MantaGallery\Livewire;

use Darvis\MantaGallery\Models\Gallery;
use Darvis\MantaGallery\Traits\GalleryTrait;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class GalleryUpdate extends Component
{
    use MantaTrait, GalleryTrait;

    public function mount(Gallery $gallery)
    {
        $this->item = $gallery;
        $this->itemOrg = translate($gallery, 'nl')['org'];
        $this->id = $gallery->id;

        $this->fill(
            $gallery->only(
                'company_id',
                'pid',
                'locale',
                'title',
                'title_2',
                'slug',
                'seo_title',
                'seo_description',
                'excerpt',
                'content',
            ),
        );
        $this->getLocaleInfo();
        $this->getBreadcrumb('update');
        $this->getTablist();
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-update');
    }


    public function save()
    {
        $this->validate();

        $row = $this->only(
            'company_id',
            'pid',
            'locale',
            'title',
            'title_2',
            'slug',
            'seo_title',
            'seo_description',
            'excerpt',
            'content',
        );
        $row['updated_by'] = auth('staff')->user()->name;
        Gallery::where('id', $this->id)->update($row);

        // return redirect()->to(route($this->route_name . '.list'));
        Flux::toast('Opgeslagen', duration: 1000, variant: 'success');
    }
}
