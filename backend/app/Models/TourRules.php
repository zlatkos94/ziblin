<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourRules extends Model
{
    use HasFactory;

    protected $table='tour_rules';

    protected $fillable = [
        'fk_client',
        'number_of_days'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
