<?php

namespace App\Infra\Database\Fixtures;

use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Infra\Database\EntitiesOrm\{Card, Establishment, Segment};

use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CreateSegmentEstablishmentAndCardFixture extends FixtureContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    public function getPriority(): int
    {
        return 0;
    }

    public function executeInProd(): bool
    {
        return true;
    }

    public function execute()
    {
        $this->entityManager->beginTransaction();
        try {
            $segmentBrands = new Segment;
            $segmentBrands->description = 'brands card';

            $this->entityManager->persist($segmentBrands);

            $segmentBeauty = new Segment;
            $segmentBeauty->description = 'beauty card';

            $this->entityManager->persist($segmentBeauty);

            $establishment = new Establishment;
            $establishment->cnpj = '43312654000163';
            $establishment->businessName = 'BrandsCard';
            $establishment->tradeName = 'BrandsCard';
            $establishment->email = 'contato@brandscard.com.br';
            $establishment->phone = '1140042896';
            $establishment->street = 'Alameda Rio Negro';
            $establishment->number = '1030';
            $establishment->complement = 'Casa';
            $establishment->neighborhood = 'Alphaville Centro Industrial';
            $establishment->city = 'Barueri';
            $establishment->state = 'SP';
            $establishment->postalCode = '06454000';

            $this->entityManager->persist($establishment);

            $cardBrands = new Card;
            $cardBrands->creditLimit = 0;
            $cardBrands->debitLimit = 0;
            $cardBrands->segment = $segmentBrands;
            $cardBrands->establishment = $establishment;

            $this->entityManager->persist($cardBrands);

            $cardBeauty = new Card;
            $cardBeauty->creditLimit = 250;
            $cardBeauty->debitLimit = 250;
            $cardBeauty->segment = $segmentBeauty;
            $cardBeauty->establishment = $establishment;

            $this->entityManager->persist($cardBeauty);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $th) {
            $this->entityManager->rollback();
        }

    }
}
