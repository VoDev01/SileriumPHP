<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public int $id;
    public string $name;
    public string $description;
    public float $priceRub;
    public int $stockAmount;
    public bool $available;
    use HasFactory;
}
