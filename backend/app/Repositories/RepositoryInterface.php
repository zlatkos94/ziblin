<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function all();

    public function create();

    public function edit($id);

    public function store(array $data);

    public function update(array $data, $id);

    public function delete($id);

}
