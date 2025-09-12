# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-09-12

### Added

- **Initial Release**: First version of the Manta Gallery module.
- **Gallery Management**: Core functionality for creating, reading, updating, and deleting galleries.
- **Livewire Components**: Includes `GalleryList`, `GalleryCreate`, `GalleryRead`, `GalleryUpdate`, and `GalleryUpload` for seamless integration with Manta CMS.
- **Database Migration**: Provides a migration to create the `manta_galleries` table with all necessary fields.
- **Gallery Model**: Eloquent model `Gallery.php` with `SoftDeletes`, `HasUploadsTrait`, and `HasTranslationsTrait`.
- **Configuration**: A `manta-gallery.php` configuration file to manage settings like route prefix and table names.
- **Service Provider**: `GalleryServiceProvider` to register components, routes, views, and migrations.
- **Traits**: `GalleryTrait` to share common logic between Livewire components.
- **Documentation**: Initial `README.md` and `project.md` files.



## Support

For support, please gallery:

- **Email**: info@arvid.nl
- **Documentation**: See README.md and MODULE_TEMPLATE.md
- **Issues**: Create an issue in the repository

## License

This project is licensed under the MIT License.
