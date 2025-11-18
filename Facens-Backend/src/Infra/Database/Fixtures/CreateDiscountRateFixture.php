<?php

namespace App\Infra\Database\Fixtures;

use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Infra\Database\EntitiesOrm\DiscountRate;

use Doctrine\ORM\EntityManagerInterface;

class CreateDiscountRateFixture extends FixtureContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {}

    public function getPriority(): int
    {
        return 2;
    }

    public function execute()
    {
        $discountRates = json_decode(file_get_contents(__DIR__.'/data/discountRate.json'));

        $this->entityManager->beginTransaction();

        $adjustedDate = DateTimeOffset::getAdjustedDateTime();

        foreach ($discountRates as $discountRate) {
            $aux = new DiscountRate;

            $aux->name = $discountRate->name;
            $aux->type = $discountRate->type;
            $aux->percentageRate = $discountRate->percentageRate;
            $aux->averagePercentageRate = $discountRate->averagePercentageRate;
            $aux->installment = $discountRate->installment;
            $aux->termInDay = $discountRate->termInDay;
            $aux->averageTermInDay = $discountRate->averageTermInDay;

            $aux->createdAt = $adjustedDate;
            $aux->updatedAt = $adjustedDate;

            $this->entityManager->persist($aux);
        }
        $this->entityManager->flush();
        $this->entityManager->commit();
    }
}
