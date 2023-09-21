<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public int $id;
    public string $name;
    public string $pageName;
    public string $image;
    use HasFactory;
}
