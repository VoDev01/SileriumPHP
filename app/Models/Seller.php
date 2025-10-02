<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Actions\EncodeImageBinaryToBase64Action;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Seller
 *
 * @mixin IdeHelperSeller
 * @property string $ulid
 * @property int $id
 * @property string $nickname
 * @property string $organization_name
 * @property int $verified
 * @property float $rating
 * @property string|null $logo
 * @property string $organization_email
 * @property int $email_verified
 * @property string $organization_type
 * @property string $tax_system
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 * @property-read int|null $products_count
 * @property-read User $user
 * @method static \Database\Factories\SellerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereOrganizationEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereOrganizationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereOrganizationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereTaxSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seller whereVerified($value)
 * @mixin \Eloquent
 */
class Seller extends Model
{
    use HasFactory, HasUlids;

    //protected $primaryKey = 'ulid';

    protected $fillable = [
        'ulid',
        'name',
        'organization_name',
        'verified',
        'rating',
        'img',
        'email',
        'email_verified',
        'logo'
    ];

    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // protected function logo(): Attribute
    // {
    //     return Attribute::get(function ($value)
    //     {
    //         return Storage::url($value);
    //     });
    // }
}
