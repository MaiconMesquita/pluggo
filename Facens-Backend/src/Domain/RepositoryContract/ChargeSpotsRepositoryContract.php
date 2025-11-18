<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\ChargeSpots;
use App\Domain\Entity\PaginatedEntities;

interface ChargeSpotsRepositoryContract
{
    /**
     * Criar um novo ChargeSpot
     */
    public function create(ChargeSpots $spot): ChargeSpots;

    /**
     * Atualizar um ChargeSpot existente
     */
    public function update(ChargeSpots $spot): ChargeSpots;

    /**
     * Buscar ChargeSpot pelo ID
     */
    public function getById(int $id, bool $loadRelationships = false): ChargeSpots;

    public function testFetchAll(): array;


    /**
     * Buscar ChargeSpots de um host específico
     */
    public function getByHostId(int $hostId, bool $loadRelationships = false): array;

    /**
     * Listar ChargeSpots com paginação
     */
    public function list(int $limit = 20, int $offset = 0): array;

    /**
     * Buscar ChargeSpots próximos de uma coordenada
     */
    public function getNearby(float $latitude, float $longitude, float $radiusKm = 5): array;
}
