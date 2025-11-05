# Usage Guide

This guide explains how to use the Manta Gallery Form package.

## Managing Gallery Forms

The module provides full CRUD functionality for gallery forms via the Manta CMS:

- **List**: Overview of all gallery forms
- **Create**: Add new gallery form
- **Edit**: Modify existing gallery form
- **View**: View gallery form details
- **Files**: Upload and manage attachments
- **Settings**: Module-specific configuration

## Managing Submissions

The same applies to gallery form submissions:

- Complete gallery details from visitors
- Form-specific information
- File management for attachments
- IP tracking for security
- Automatic email notifications

## Programmatic Usage

### Creating Gallery Forms

```php
use Darvis\MantaGallery\Models\Gallery;

// Create new gallery form
$galleryForm = Gallery::create([
    'title' => 'General Gallery Form',
    'subtitle' => 'Get in touch with us',
    'content' => 'Please fill out the form below...',
    'data' => ['required_fields' => ['name', 'email', 'message']]
]);
```

### Handling Submissions

```php
use Darvis\MantaGallery\Models\GallerySubmission;

// Add submission
$submission = GallerySubmission::create([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@example.com',
    'subject' => 'General Inquiry',
    'comment' => 'I would like more information about...'
]);
```

## Frontend Integration

For frontend gallery forms, you can use the submission model directly:

```php
// In your controller
use Darvis\MantaGallery\Models\GallerySubmission;
use Illuminate\Http\Request;

public function store(Request $request)
{
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'comment' => 'required|string',
    ]);

    GallerySubmission::create($validated);
    
    return response()->json(['message' => 'Message sent successfully']);
}
```

### Frontend Form Example

```html
<form action="/api/gallery" method="POST">
    @csrf
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" required>
        </div>
        <div>
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname" required>
        </div>
    </div>
    
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
    </div>
    
    <div>
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" required>
    </div>
    
    <div>
        <label for="comment">Message</label>
        <textarea name="comment" id="comment" rows="5" required></textarea>
    </div>
    
    <button type="submit">Send Message</button>
</form>
```

## Admin Interface

### Accessing the Admin

1. Log in to your Manta CMS admin panel
2. Navigate to the Gallery section
3. Use the interface to manage forms and submissions

### Available Routes

All admin routes are protected with staff middleware:

#### Gallery Form Management Routes
- `GET /gallery` - Gallery forms overview
- `GET /gallery/create` - Create new gallery form
- `GET /gallery/{id}` - View gallery form details
- `GET /gallery/{id}/edit` - Edit gallery form
- `GET /gallery/{id}/files` - File management
- `GET /gallery/settings` - Module settings

## Email Notifications

The package automatically sends email notifications when new submissions are received. Configure email settings in the [configuration file](configuration.md).

## File Uploads

The package supports file uploads for gallery form submissions. Files are managed through the Manta CMS file management system.

## Next Steps

- [Understand the database schema](database.md)
- [View troubleshooting guide](troubleshooting.md)
- [Learn about API endpoints](api.md)
