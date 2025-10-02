<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Actions\EncodeImageBinaryToBase64Action;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Category
 *
 * @mixin IdeHelperCategory
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property string $pageName
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subcategory> $subcategories
 * @property-read int|null $subcategories_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePageName($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "image",
        "pageName"
    ];

    public $timestamps = false;

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    // protected function image(): Attribute
    // {
    //     return Attribute::get(function ($value)
    //     {
    //         return Storage::url($value);
    //     });
    // }
}
