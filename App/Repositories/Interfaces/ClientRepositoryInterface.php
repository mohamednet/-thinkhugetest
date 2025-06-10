<?php
namespace App\Repositories\Interfaces;

interface ClientRepositoryInterface extends RepositoryInterface
{
    public function findByName($name);
    public function findByEmail($email);
    public function count();
    public function findRecent($limit);
}
