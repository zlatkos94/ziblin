<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Region;
use App\Models\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ClientRepository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;

    }

    // Get all instances of model
    public function all()
    {
        $auth= Auth::user();
        if ($auth->name === 'admin') {

            return $this->model::join('users', 'users.id', '=', 'clients.fk_user')
                ->join('regions', 'regions.id', '=', 'clients.fk_region')
                ->select('clients.*', 'users.name as user_name', 'regions.name as region_name', 'regions.description as region_description')
                ->get();
        }
        else {

            return $this->model::join('users', 'users.id', '=', 'clients.fk_user')
                ->join('regions', 'regions.id', '=', 'clients.fk_region')
                ->select('clients.*', 'users.name as user_name', 'regions.name as region_name', 'regions.description as region_description')
                ->where('users.id',$auth->id)
                ->get();
        }

//        if(Auth::user()->checkRole())
//        {
//            return $this->model::join('users', 'users.id', '=', 'clients.fk_user')
//                ->join('regions', 'regions.id', '=', 'clients.fk_region')
//                ->select('clients.*', 'users.name as user_name', 'regions.name as region_name', 'regions.description as region_description')
//                ->get();
//        }
    }


    public function create()
    {
        return DB::table('regions')->select('regions.name as label', 'regions.id as value')->get();
    }

    // create a new record in the database
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function edit($id)
    {
//        $regions = DB::table('regions')->select('regions.name as label', 'regions.id as value')->get();
//        $client = DB::table('clients')->where('id', '=', $id)->first();
//        $users = DB::table('users')->select('users.name as label', 'users.id as value')->get();

        $regions = (new Region())->DropDownRegion();
        $client = DB::table('clients')->where('id', '=', $id)->first();
        $users = (new Users())->DropDownUser();

        return ['regions' => $regions, 'client' => $client, 'users' => $users];
    }

    public function update(array $data, $id)
    {
        $this->model = $this->model->find($id);
        return $this->model->update($data);
    }

    public function delete($id)
    {

        return $this->model::find($id)->delete();

    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }
}

