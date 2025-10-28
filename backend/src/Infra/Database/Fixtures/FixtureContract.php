<?php

namespace App\Infra\Database\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
abstract class FixtureContract
{

    protected EntityManagerInterface $entityManager;

    /**
     * Define a prioridade da execução, quanto menor será executado primeiro.
     */
    public function getPriority(): int
    {
        return 1;
    }
    public function executeInDev(): bool
    {
        return true;
    }
    public function executeInProd(): bool
    {
        return false;
    }
        
    public abstract function execute();
}
