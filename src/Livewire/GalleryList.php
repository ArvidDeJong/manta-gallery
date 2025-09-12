<?php

namespace Darvis\MantaGallery\Livewire;

use Darvis\MantaGallery\Models\Gallery;
use Darvis\MantaGallery\Traits\GalleryTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Manta\FluxCMS\Traits\MantaTrait;
use Manta\FluxCMS\Traits\SortableTrait;
use Manta\FluxCMS\Traits\WithSortingTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class GalleryList extends Component
{
    use GalleryTrait;
    use WithPagination;
    use SortableTrait;
    use MantaTrait;
    use WithSortingTrait;

    public function mount()
    {
        $this->getBreadcrumb();
        $this->sortBy = 'title';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $this->trashed = count(Gallery::whereNull('pid')->onlyTrashed()->get());

        $obj = Gallery::whereNull('pid');
        if ($this->tablistShow == 'trashed') {
            $obj->onlyTrashed();
        }
        $obj = $this->applySorting($obj);
        $obj = $this->applySearch($obj);
        $items = $obj->paginate(50);
        return view('manta-gallery::livewire.gallery-list', ['items' => $items]);
    }
}
