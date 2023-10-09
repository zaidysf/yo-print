<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'unique_key',
        'product_title',
        'product_description',
        'style',
        'sanmar_mainframe_color',
        'size',
        'color_name',
        'piece_price',
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    public function getCsvHeader(): array
    {
        return [
            'unique_key' => 'UNIQUE_KEY',
            'product_title' => 'PRODUCT_TITLE',
            'product_description' => 'PRODUCT_DESCRIPTION',
            'style' => 'STYLE#',
            'sanmar_mainframe_color' => 'SANMAR_MAINFRAME_COLOR',
            'size' => 'SIZE',
            'color_name' => 'COLOR_NAME',
            'piece_price' => 'PIECE_PRICE',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
