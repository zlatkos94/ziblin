<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    use HasFactory;

    protected $table='roadmap';

    protected $fillable = [

        'fk_client',
        'fk_promo_material',
        'fk_user',
        'applies_to_date',
        'description',

    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function promMaterial()
    {
        return $this->belongsTo(PromoMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

}
