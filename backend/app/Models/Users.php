<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    use SoftDeletes;

    /**
     * Get the notes for the users.
     */
    public function notes()
    {
        return $this->hasMany('App\Notes');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function DropDownUser()
    {
        return DB::table('users')->select('users.name as label', 'users.id as value')->get();
    }

//    public function checkRole(): bool
//    {
//        $auth= Auth::user();
//
//        if($this->$auth==='admin') {
//            return true;
//        }
//        return false;
//    }

    protected $dates = [
        'deleted_at'
    ];
}
