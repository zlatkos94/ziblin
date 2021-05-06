<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class RoadmapRepository implements RepositoryInterface
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
        return $this->model::join('users', 'users.id', '=', 'roadmap.fk_user')
            ->join('promo_material', 'promo_material.id', '=', 'roadmap.fk_promo_material')
            ->join('clients', 'clients.id', '=', 'roadmap.fk_client')
            ->select('roadmap.*', 'users.name as user_name', 'promo_material.name as material_name',
                'clients.company_name as client_company_name','clients.client_name as client_client_name')
            ->get();
    }

    public function create()
    {
//        $response = $this->model->create();
//
//        $clients = $response['clients'];
//        $promoMaterials = $response['promoMaterials'];
//
//        return response()->json( [ 'clients' => $clients, 'promoMaterials' => $promoMaterials] );


        $clients = DB::table('clients')->select('clients.company_name as label', 'clients.id as value')->get();

        $promoMaterials = DB::table('promo_material')->select('promo_material.name as label', 'promo_material.id as value')->get();

        return ['client' => $clients, 'promoMaterials' => $promoMaterials];

    }

    // create a new record in the database
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function edit($id)
    {
        $regions = DB::table('regions')->select('regions.name as label', 'regions.id as value')->get();
        $client = DB::table('clients')->where('id', '=', $id)->first();
        $users = DB::table('users')->select('users.name as label', 'users.id as value')->get();

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

