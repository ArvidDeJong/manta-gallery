# Foto's Ophalen van Albums

Deze documentatie legt uit hoe je foto's van een specifiek album kunt ophalen op basis van de album slug.

## Album Model

Het Gallery model heeft de volgende relevante eigenschappen:
- `slug`: Unieke identificatie voor het album
- `title`: Titel van het album
- `active`: Of het album actief/zichtbaar is

Albums gebruiken de `HasUploadsTrait` wat betekent dat ze gekoppeld zijn aan uploads (foto's).

## Foto's Ophalen op Basis van Album Slug

### Basis Implementatie

```php
use Darvis\MantaGallery\Models\Gallery;

// Haal een album op basis van slug
$album = Gallery::where('slug', $albumSlug)
    ->where('active', true)
    ->first();

if (!$album) {
    // Album niet gevonden
    return response()->json(['error' => 'Album niet gevonden'], 404);
}

// Haal alle foto's van het album op
$photos = $album->uploads()
    ->where('image', true) // Alleen afbeeldingen
    ->orderBy('sort', 'asc')
    ->orderBy('created_at', 'asc')
    ->get();
```

### Uitgebreide Implementatie met Metadata

```php
use Darvis\MantaGallery\Models\Gallery;

public function getAlbumPhotos(string $albumSlug)
{
    $album = Gallery::where('slug', $albumSlug)
        ->where('active', true)
        ->first();

    if (!$album) {
        return response()->json(['error' => 'Album niet gevonden'], 404);
    }

    $photos = $album->uploads()
        ->where('image', true)
        ->orderBy('sort', 'asc')
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($photo) {
            $imageData = $photo->getImage();
            $thumbnailData = $photo->getImage(300); // 300px breedte thumbnail
            
            return [
                'id' => $photo->id,
                'title' => $photo->title,
                'filename' => $photo->filename,
                'original_filename' => $photo->filenameOriginal,
                'alt_text' => $photo->alt ?? $photo->title,
                'description' => $photo->description,
                'sort_order' => $photo->sort,
                'is_main' => (bool) $photo->main,
                'urls' => [
                    'original' => $imageData['url'] ?? null,
                    'thumbnail' => $thumbnailData['url'] ?? null,
                ],
                'dimensions' => [
                    'width' => $imageData['width'] ?? null,
                    'height' => $imageData['height'] ?? null,
                ],
                'file_size' => $photo->size,
                'mime_type' => $photo->mime,
                'created_at' => $photo->created_at,
                'updated_at' => $photo->updated_at,
            ];
        });

    return response()->json([
        'album' => [
            'id' => $album->id,
            'title' => $album->title,
            'slug' => $album->slug,
            'description' => $album->content,
            'photo_count' => $photos->count(),
        ],
        'photos' => $photos
    ]);
}
```

### Controller Voorbeeld

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darvis\MantaGallery\Models\Gallery;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    /**
     * Haal foto's van een album op basis van slug
     */
    public function getAlbumPhotos(string $slug): JsonResponse
    {
        $album = Gallery::where('slug', $slug)
            ->where('active', true)
            ->first();

        if (!$album) {
            return response()->json([
                'error' => 'Album niet gevonden',
                'message' => "Album met slug '{$slug}' bestaat niet of is niet actief."
            ], 404);
        }

        $photos = $album->uploads()
            ->where('image', true)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'alt_text' => $photo->alt ?? $photo->title,
                    'description' => $photo->description,
                    'is_main' => (bool) $photo->main,
                    'urls' => [
                        'original' => $photo->getImage()['url'] ?? null,
                        'large' => $photo->getImage(1200)['url'] ?? null,
                        'medium' => $photo->getImage(800)['url'] ?? null,
                        'thumbnail' => $photo->getImage(300)['url'] ?? null,
                    ],
                    'dimensions' => [
                        'width' => $photo->getImage()['width'] ?? null,
                        'height' => $photo->getImage()['height'] ?? null,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'album' => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'description' => $album->content,
                'created_at' => $album->created_at,
            ],
            'photos' => $photos,
            'meta' => [
                'total_photos' => $photos->count(),
                'has_photos' => $photos->count() > 0,
            ]
        ]);
    }

    /**
     * Haal alleen de hoofdfoto van een album
     */
    public function getAlbumMainPhoto(string $slug): JsonResponse
    {
        $album = Gallery::where('slug', $slug)
            ->where('active', true)
            ->first();

        if (!$album) {
            return response()->json(['error' => 'Album niet gevonden'], 404);
        }

        $mainPhoto = $album->uploads()
            ->where('image', true)
            ->where('main', true)
            ->first();

        // Als er geen hoofdfoto is, neem de eerste foto
        if (!$mainPhoto) {
            $mainPhoto = $album->uploads()
                ->where('image', true)
                ->orderBy('sort', 'asc')
                ->orderBy('created_at', 'asc')
                ->first();
        }

        if (!$mainPhoto) {
            return response()->json(['error' => 'Geen foto\'s gevonden in dit album'], 404);
        }

        return response()->json([
            'success' => true,
            'album_title' => $album->title,
            'photo' => [
                'id' => $mainPhoto->id,
                'title' => $mainPhoto->title,
                'alt_text' => $mainPhoto->alt ?? $mainPhoto->title,
                'urls' => [
                    'original' => $mainPhoto->getImage()['url'] ?? null,
                    'large' => $mainPhoto->getImage(1200)['url'] ?? null,
                    'medium' => $mainPhoto->getImage(800)['url'] ?? null,
                    'thumbnail' => $mainPhoto->getImage(300)['url'] ?? null,
                ],
            ]
        ]);
    }
}
```

### Route Definitie

```php
// routes/api.php
use App\Http\Controllers\GalleryController;

Route::get('/albums/{slug}/photos', [GalleryController::class, 'getAlbumPhotos']);
Route::get('/albums/{slug}/main-photo', [GalleryController::class, 'getAlbumMainPhoto']);

// routes/web.php (voor web routes)
Route::get('/gallery/{slug}', [GalleryController::class, 'getAlbumPhotos']);
```

## Frontend Gebruik

### JavaScript/Ajax Voorbeeld

```javascript
// Haal album foto's op
async function loadAlbumPhotos(albumSlug) {
    try {
        const response = await fetch(`/api/albums/${albumSlug}/photos`);
        const data = await response.json();
        
        if (data.success) {
            displayPhotos(data.photos);
            updateAlbumInfo(data.album);
        } else {
            console.error('Fout bij ophalen foto\'s:', data.error);
        }
    } catch (error) {
        console.error('Network error:', error);
    }
}

// Toon foto's in de interface
function displayPhotos(photos) {
    const container = document.getElementById('photo-gallery');
    container.innerHTML = '';
    
    photos.forEach(photo => {
        const photoElement = document.createElement('div');
        photoElement.className = 'photo-item';
        photoElement.innerHTML = `
            <img src="${photo.urls.medium}" 
                 alt="${photo.alt_text}" 
                 title="${photo.title}"
                 loading="lazy">
            <div class="photo-info">
                <h3>${photo.title}</h3>
                <p>${photo.description || ''}</p>
            </div>
        `;
        container.appendChild(photoElement);
    });
}
```

### Blade Template Voorbeeld

```blade
{{-- resources/views/gallery/album.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="album-container">
    <h1>{{ $album->title }}</h1>
    
    @if($album->content)
        <div class="album-description">
            {!! $album->content !!}
        </div>
    @endif
    
    @if($photos->count() > 0)
        <div class="photo-grid">
            @foreach($photos as $photo)
                <div class="photo-item">
                    <img src="{{ $photo->getImage(400)['url'] }}" 
                         alt="{{ $photo->alt ?? $photo->title }}"
                         title="{{ $photo->title }}"
                         loading="lazy">
                    
                    @if($photo->title || $photo->description)
                        <div class="photo-caption">
                            @if($photo->title)
                                <h3>{{ $photo->title }}</h3>
                            @endif
                            @if($photo->description)
                                <p>{{ $photo->description }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="no-photos">Dit album bevat nog geen foto's.</p>
    @endif
</div>
@endsection
```

## Geavanceerde Query's

### Filteren op Foto Eigenschappen

```php
// Alleen hoofdfoto's
$mainPhotos = $album->uploads()
    ->where('image', true)
    ->where('main', true)
    ->get();

// Foto's met specifieke afmetingen
$landscapePhotos = $album->uploads()
    ->where('image', true)
    ->whereRaw('JSON_EXTRACT(data, "$.width") > JSON_EXTRACT(data, "$.height")')
    ->get();

// Foto's groter dan bepaalde bestandsgrootte (in bytes)
$largePhotos = $album->uploads()
    ->where('image', true)
    ->where('size', '>', 1048576) // > 1MB
    ->get();
```

### Paginatie

```php
use Illuminate\Http\Request;

public function getAlbumPhotos(string $slug, Request $request)
{
    $album = Gallery::where('slug', $slug)
        ->where('active', true)
        ->firstOrFail();

    $perPage = $request->get('per_page', 20);
    $photos = $album->uploads()
        ->where('image', true)
        ->orderBy('sort', 'asc')
        ->orderBy('created_at', 'asc')
        ->paginate($perPage);

    return response()->json([
        'album' => $album->only(['id', 'title', 'slug']),
        'photos' => $photos->items(),
        'pagination' => [
            'current_page' => $photos->currentPage(),
            'last_page' => $photos->lastPage(),
            'per_page' => $photos->perPage(),
            'total' => $photos->total(),
        ]
    ]);
}
```

## Foutafhandeling

```php
public function getAlbumPhotos(string $slug)
{
    try {
        $album = Gallery::where('slug', $slug)
            ->where('active', true)
            ->first();

        if (!$album) {
            return response()->json([
                'error' => 'Album niet gevonden',
                'code' => 'ALBUM_NOT_FOUND'
            ], 404);
        }

        $photos = $album->uploads()
            ->where('image', true)
            ->orderBy('sort', 'asc')
            ->get();

        if ($photos->isEmpty()) {
            return response()->json([
                'message' => 'Album bevat geen foto\'s',
                'album' => $album->only(['id', 'title', 'slug']),
                'photos' => [],
                'code' => 'NO_PHOTOS'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'album' => $album->only(['id', 'title', 'slug', 'content']),
            'photos' => $photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'url' => $photo->getImage()['url'] ?? null,
                ];
            })
        ]);

    } catch (\Exception $e) {
        \Log::error('Fout bij ophalen album foto\'s', [
            'slug' => $slug,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'error' => 'Er is een fout opgetreden',
            'code' => 'INTERNAL_ERROR'
        ], 500);
    }
}
```

## Caching

Voor betere performance kun je caching toevoegen:

```php
use Illuminate\Support\Facades\Cache;

public function getAlbumPhotos(string $slug)
{
    $cacheKey = "album_photos_{$slug}";
    
    return Cache::remember($cacheKey, 3600, function () use ($slug) {
        $album = Gallery::where('slug', $slug)
            ->where('active', true)
            ->first();

        if (!$album) {
            return null;
        }

        return [
            'album' => $album->only(['id', 'title', 'slug']),
            'photos' => $album->uploads()
                ->where('image', true)
                ->orderBy('sort', 'asc')
                ->get()
                ->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'title' => $photo->title,
                        'url' => $photo->getImage()['url'],
                    ];
                })
        ];
    });
}
```

## Samenvatting

Met deze implementaties kun je eenvoudig foto's van een album ophalen op basis van de slug:

1. **Basis ophalen**: `Gallery::where('slug', $slug)->first()->uploads()`
2. **Met metadata**: Gebruik `getImage()` methode voor verschillende formaten
3. **API endpoints**: Maak RESTful endpoints voor frontend gebruik
4. **Foutafhandeling**: Controleer altijd of album bestaat en actief is
5. **Performance**: Gebruik caching en paginatie voor grote albums

De Gallery model gebruikt de `HasUploadsTrait` wat zorgt voor de koppeling met foto's via de uploads relatie.
