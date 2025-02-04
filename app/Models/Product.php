<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Tentukan field yang dapat dimasukkan secara massal
    protected $fillable = [
        'name',
        'price',
        'stock',
        'size',
        'image_path'

        // field lainnya

    ];
    protected $casts = [
        'size' => 'array',  // Jika menggunakan database MySQL dengan tipe JSON untuk ukuran
    ];
    // Product.php
    // Di dalam model Product.php
    // Di dalam model Product.php
    // Di dalam model Product.php
    // Di dalam model Product.php
    public function productSizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id'); // Menghubungkan produk dengan relasi ProductSize
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_product', 'product_id', 'transaction_id');
    }

    // ProductSize.php
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function size()
    {
        return $this->belongsTo(ProductSize::class); // Pastikan ada model Size yang terkait
    }
    // Pastikan relasi sudah benar di model Product
    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }


    public $timestamps = true;
}