# API Documentation

This document describes the API endpoints and programmatic usage of the Manta Gallery Form package.

## Models

### Gallery Model

The main model for gallery form submissions.

```php
use Darvis\MantaGallery\Models\Gallery;

// Create a new gallery
$gallery = Gallery::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'General Inquiry',
    'comment' => 'I would like more information...'
]);

// Find galleries
$gallery = Gallery::find(1);
$galleries = Gallery::where('active', true)->get();
$recentGalleries = Gallery::latest()->take(10)->get();

// Update gallery
$gallery->update([
    'comment_internal' => 'Follow up required'
]);

// Soft delete
$gallery->delete();
```

## Available Methods

### Query Scopes

```php
// Active gallerys only
Gallery::active()->get();

// By company
Gallery::where('company_id', 1)->get();

// Recent submissions
Gallery::recent()->get();

// Search by email
Gallery::where('email', 'like', '%@example.com')->get();
```

### Relationships

```php
// Get gallery with files (if using uploads)
$gallery = Gallery::with('uploads')->find(1);

// Get gallery creator
$gallery = Gallery::with('creator')->find(1);
```

## REST API Endpoints

### Frontend API Routes

Create these routes in your application for frontend integration:

```php
// routes/api.php
use App\Http\Controllers\GalleryController;

Route::post('/gallery', [GalleryController::class, 'store']);
Route::get('/gallery-forms', [GalleryController::class, 'forms']);
```

### Example Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darvis\MantaGallery\Models\Gallery;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    /**
     * Store a new gallery submission
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'comment' => 'required|string',
            'newsletters' => 'boolean',
        ]);

        // Add IP address and timestamp
        $validated['ip'] = $request->ip();
        $validated['active'] = true;

        $gallery = gallery::create($validated);

        return response()->json([
            'message' => 'Gallery form submitted successfully',
            'id' => $gallery->id
        ], 201);
    }

    /**
     * Get available gallery forms
     */
    public function forms(): JsonResponse
    {
        $forms = gallery::active()
            ->select('id', 'title', 'subtitle', 'content')
            ->get();

        return response()->json($forms);
    }

    /**
     * Get gallery by ID
     */
    public function show(int $id): JsonResponse
    {
        $gallery = gallery::findOrFail($id);

        return response()->json($gallery);
    }
}
```

## Validation Rules

### Standard Validation

```php
$rules = [
    'firstname' => 'required|string|max:255',
    'lastname' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'phone' => 'nullable|string|max:255',
    'company' => 'nullable|string|max:255',
    'subject' => 'required|string|max:255',
    'comment' => 'required|string|max:1000',
    'newsletters' => 'boolean',
];
```

### Extended Validation

```php
$rules = [
    // Basic fields
    'firstname' => 'required|string|max:255',
    'lastname' => 'required|string|max:255',
    'email' => 'required|email|max:255|unique:manta_gallerys,email',
    
    // Optional fields
    'phone' => 'nullable|string|max:255',
    'company' => 'nullable|string|max:255',
    'address' => 'nullable|string|max:255',
    'zipcode' => 'nullable|string|max:10',
    'city' => 'nullable|string|max:255',
    'country' => 'nullable|string|max:255',
    
    // Message fields
    'subject' => 'required|string|max:255',
    'comment' => 'required|string|max:2000',
    
    // Preferences
    'newsletters' => 'boolean',
    
    // Custom fields
    'option_1' => 'nullable|string|max:1000',
    'option_2' => 'nullable|string|max:1000',
];
```

## Events

### Model Events

```php
use Darvis\MantaGallery\Models\Gallery;

// Listen for gallery creation
gallery::created(function ($gallery) {
    // Send notification email
    Mail::to(config('manta-gallery.email.default_receivers'))
        ->send(new GallerySubmissionMail($gallery));
});

// Listen for gallery updates
gallery::updated(function ($gallery) {
    // Log the update
    Log::info('Gallery updated', ['id' => $gallery->id]);
});
```

## Custom Fields

### Using Option Fields

```php
// Store custom data in option fields
$gallery = Gallery::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'Product Inquiry',
    'comment' => 'I need more information',
    'option_1' => 'Product A',
    'option_2' => 'Urgent',
    'option_3' => json_encode(['source' => 'website', 'campaign' => 'summer2024'])
]);

// Retrieve custom data
$productInterest = $gallery->option_1;
$priority = $gallery->option_2;
$metadata = json_decode($gallery->option_3, true);
```

### Using JSON Data Field

```php
// Store complex data in JSON field
$gallery = Gallery::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'Support Request',
    'comment' => 'I need help with...',
    'data' => json_encode([
        'source' => 'gallery_form',
        'utm_campaign' => 'spring_promotion',
        'user_agent' => request()->userAgent(),
        'referrer' => request()->header('referer'),
        'custom_fields' => [
            'department' => 'sales',
            'priority' => 'high'
        ]
    ])
]);

// Query JSON data
$salesGallerys = gallery::whereJsonContains('data->custom_fields->department', 'sales')->get();
```

## Bulk Operations

### Bulk Insert

```php
$gallerys = [
    [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john@example.com',
        'subject' => 'Inquiry 1',
        'comment' => 'Message 1',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'email' => 'jane@example.com',
        'subject' => 'Inquiry 2',
        'comment' => 'Message 2',
        'created_at' => now(),
        'updated_at' => now(),
    ]
];

gallery::insert($gallerys);
```

### Bulk Update

```php
// Mark all gallerys from specific company as processed
gallery::where('company', 'Example Corp')
    ->update(['comment_internal' => 'Processed by sales team']);
```

## Export Functions

### CSV Export

```php
use League\Csv\Writer;

public function exportGallerys()
{
    $gallerys = gallery::select([
        'firstname', 'lastname', 'email', 'phone', 
        'company', 'subject', 'comment', 'created_at'
    ])->get();

    $csv = Writer::createFromString('');
    $csv->insertOne([
        'First Name', 'Last Name', 'Email', 'Phone',
        'Company', 'Subject', 'Message', 'Date'
    ]);

    foreach ($gallerys as $gallery) {
        $csv->insertOne([
            $gallery->firstname,
            $gallery->lastname,
            $gallery->email,
            $gallery->phone,
            $gallery->company,
            $gallery->subject,
            $gallery->comment,
            $gallery->created_at->format('Y-m-d H:i:s')
        ]);
    }

    return response($csv->toString())
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="gallerys.csv"');
}
```

## Security Considerations

### Rate Limiting

```php
// In routes/api.php
Route::middleware(['throttle:10,1'])->group(function () {
    Route::post('/gallery', [GalleryController::class, 'store']);
});
```

### Input Sanitization

```php
use Illuminate\Support\Str;

$validated['comment'] = Str::limit(strip_tags($validated['comment']), 2000);
$validated['subject'] = strip_tags($validated['subject']);
```

### CSRF Protection

```php
// For web forms
<form method="POST" action="/gallery">
    @csrf
    <!-- form fields -->
</form>
```

## Next Steps

- [Learn about usage](usage.md)
- [Understand configuration](configuration.md)
- [View troubleshooting guide](troubleshooting.md)
