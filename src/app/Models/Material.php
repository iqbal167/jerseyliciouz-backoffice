<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'cost_per_kg',
        'cost_ratio',
        'cost_per_unit',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_textile' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class);
    }
}
