<?php

namespace Darvis\MantaGallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Manta\FluxCMS\Traits\HasTranslationsTrait;
use Manta\FluxCMS\Traits\HasUploadsTrait;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUploadsTrait;
    use HasTranslationsTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manta_galleries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'pid',
        'locale',
        'active',
        'sort',
        'title',
        'title_2',
        'title_3',
        'slug',
        'seo_title',
        'seo_description',
        'tags',
        'summary',
        'excerpt',
        'content',
        'homepage',
        'locked',
        'fullpage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'homepage' => 'boolean',
        'locked' => 'boolean',
        'fullpage' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'pid',
        'locale',
        'active',
        'sort',
        'title',
        'title_2',
        'title_3',
        'slug',
        'seo_title',
        'seo_description',
        'tags',
        'summary',
        'excerpt',
        'content',
        'homepage',
        'locked',
        'fullpage',
    ];

    /**
     * Boot de model events voor het automatisch vastleggen van gebruikersinformatie.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->created_by = $user->name;
            }
        });

        static::updating(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->updated_by = $user->name;
            }
        });

        static::deleting(function ($item) {
            $user = Auth::guard('staff')->user();
            if ($user) {
                $item->deleted_by = $user->name;
            }
        });
    }
}
