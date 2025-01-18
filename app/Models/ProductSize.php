<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = ['size', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withPivot('quantity', 'price');
    }
}