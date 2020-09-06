<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id');
    }

    public function history()
    {
        return $this->hasMany('App\Models\ProductHistory', 'product_id');
    }
}
