<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'link_text',
        'order',
        'is_active',
        'show_image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_image' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope para slides ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }
}
