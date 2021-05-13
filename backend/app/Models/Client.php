<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;

    protected $table='clients';

    protected $fillable = [

        'company_name',
        'client_name',
        'address',
        'city',
        'phone',
        'facebook',
        'instagram',
        'mail',
        'lat',
        'long',
        'description',
        'fk_user',
        'fk_region'

    ];
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function tourRules()
    {
        return $this->hasMany(TourRules::class);
    }

    public function DropDownClient()
    {
//        $clients = Client::select('clients.company_name as label', 'clients.id as value')->get();
//        return $clients;

        return Client::select('clients.company_name as label', 'clients.id as value')->get();
    }

}
