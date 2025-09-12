<?php

return [
    "name" => "gallery",
    "title" => "Albums",
    "module_name" => [
        "single" => "Album",
        "multiple" => "Albums"
    ],
    'upload_data' => [
        'fields' =>
        [
            'title_1' => ['type' => 'string', 'required' => false, 'title' => 'Titel 1'],
            // 'title_2' => ['type' => 'string', 'required' => false, 'title' => 'Titel 2'],
            // 'title_3' => ['type' => 'string', 'required' => false, 'title' => 'Titel 3'],
            'button_1' => ['type' => 'string', 'required' => false, 'title' => 'Titel knop 1'],
            'url_1' => ['type' => 'route', 'required' => false, 'title' => 'Route knop 1'],
            'button_2' => ['type' => 'string', 'required' => false, 'title' => 'Titel knop 2'],
            'url_2' => ['type' => 'route', 'required' => false, 'title' => 'Route knop 2']
        ]
    ],
    "fields" => [
        "uploads" => [
            "active" => true,
            "type" => "",
            "title" => "Uploads",
            "read" => false,
            "required" => false,
        ],
        "locale" => [
            "active" => false,
            "type" => "text",
            "title" => "Taal",
            "read" => true
        ],
        "title" => [
            "active" => true,
            "type" => "text",
            "title" => "Titel",
            "read" => true
        ],
        "title_2" => [
            "active" => false,
            "type" => "text",
            "title" => "Subtitel",
            "read" => true
        ],
        "slug" => [
            "active" => true,
            "type" => "text",
            "title" => "Slug",
            "read" => true
        ],
        "seo_title" => [
            "active" => false,
            "type" => "text",
            "title" => "SEO titel",
            "read" => true
        ],
        "seo_description" => [
            "active" => false,
            "type" => "text",
            "title" => "SEO omschrijving",
            "read" => true
        ],
        "tags" => [
            "active" => false,
            "type" => "textarea",
            "title" => "Tags",
            "read" => true
        ],
        "excerpt" => [
            "active" => false,
            "type" => "textarea",
            "title" => "Inleiding",
            "read" => true
        ],
        "content" => [
            "active" => false,
            "type" => "editor",
            "title" => "Bericht",
            "read" => true
        ],
        "summary" => [
            "active" => false,
            "type" => "textarea",
            "title" => "Samenvatting",
            "read" => true
        ],
        "title_3" => [
            "active" => false,
            "type" => "text",
            "title" => "Titel 3",
            "read" => true
        ],
        "active" => [
            "active" => false,
            "type" => "checkbox",
            "title" => "Actief",
            "read" => true
        ],
        "sort" => [
            "active" => false,
            "type" => "text",
            "title" => "Sorteer",
            "read" => true
        ],
        "homepage" => [
            "active" => false,
            "type" => "checkbox",
            "title" => "Homepage",
            "read" => true
        ],
        "locked" => [
            "active" => false,
            "type" => "checkbox",
            "title" => "Gesoigneerd",
            "read" => true
        ],
        "fullpage" => [
            "active" => false,
            "type" => "checkbox",
            "title" => "Volledige pagina",
            "read" => true
        ]
    ]
];
