<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function DropDownPromoMaterial()
    {
        return DB::table('promo_material')->select('promo_material.name as label', 'promo_material.id as value')->get();
    }

}
