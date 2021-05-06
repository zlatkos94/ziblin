<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    use HasFactory;

    protected $table='regions';

    protected $fillable = [

        'name',
        'description'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function client()
    {
        return $this->hasMany(Client::class);
    }

    public function DropDownRegion()
    {
        return DB::table('regions')->select('regions.name as label', 'regions.id as value')->get();;
    }
}
