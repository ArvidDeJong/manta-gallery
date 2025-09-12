<?php

namespace Darvis\MantaGallery\Livewire;

use Darvis\MantaGallery\Models\Gallery;
use Darvis\MantaGallery\Traits\GalleryTrait;
use Illuminate\Http\Request;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class GalleryRead extends Component
{
    use MantaTrait, GalleryTrait;

    public function mount(Request $request, Gallery $gallery)
    {
        $this->item = $gallery;
        $this->itemOrg = $gallery;
        $this->locale = $gallery->locale;
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $gallery->id;
            $this->locale = $request->input('locale');
            $item_translate = Gallery::where(['pid' => $gallery->id, 'locale' => $request->input('locale')])->first();
            $this->item = $item_translate;
        }

        if ($gallery) {
            $this->id = $gallery->id;
        }
        $this->getLocaleInfo();
        $this->getBreadcrumb();
        $this->getTablist();
    }

    public function render()
    {
        return view('manta-cms::livewire.default.manta-default-read');
    }
}
