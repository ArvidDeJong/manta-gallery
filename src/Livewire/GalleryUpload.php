<?php

namespace Darvis\MantaGallery\Livewire;

use Darvis\MantaGallery\Models\Gallery;
use Darvis\MantaGallery\Traits\GalleryTrait;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class GalleryUpload extends Component
{
    use MantaTrait, GalleryTrait;

    public function mount(Gallery $gallery)
    {
        $this->item = $gallery;
        $this->itemOrg = $gallery;
        $this->id = $gallery->id;
        $this->locale = $gallery->locale;



        $this->getLocaleInfo();
        $this->getBreadcrumb('upload');
        $this->getTablist();
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-upload');
    }
}
