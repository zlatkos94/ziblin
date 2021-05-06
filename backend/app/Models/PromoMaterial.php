<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoMaterial extends Model
{
    use HasFactory;

    protected $table='promo_material';

    protected $fillable = [

        'name',
        'description'
    ];

    public function roadmap()
    {
        return $this->hasMany(Roadmap::class);
    }

}
