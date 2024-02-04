<?php

namespace App\Repositories;

interface ContactRepositoryInterface
{
    public function all(array $data);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
