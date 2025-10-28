<?php

namespace App\Infra\Database\Fixtures;

use App\Infra\Database\Doctrine;

class LoadFixtures
{
    /**
     * @return array<FixtureContract>
     */
    public function loadFixtures(bool $isProd): array
    {
        $entityManager = Doctrine::getInstance()->getEntityManager();
        $fixtures =
            array_filter(
                [
                    new CreateRootFixture($entityManager),
                    new CreateSegmentEstablishmentAndCardFixture($entityManager),
                    new CreateDiscountRateFixture($entityManager),
                ],
                function (FixtureContract $fixture) use ($isProd) {
                    return ( (
                        ($isProd && $fixture->executeInProd()) ||
                        (!$isProd && $fixture->executeInDev())                        
                    )
                    );
                }
            );

        usort(
            $fixtures,
            function (
                FixtureContract $fixturePrevious,
                FixtureContract $fixtureNext
            ) {
                return $fixturePrevious->getPriority() > $fixtureNext->getPriority();
            }
        );

        return $fixtures;
    }
}
