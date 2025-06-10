<?php
namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    public function findByClientId($clientId);
    public function findByType($type);
    public function findByDateRange($startDate, $endDate);
    public function getClientBalance($clientId);
    public function getTotalByType($type);
    public function findRecent($limit);
}
