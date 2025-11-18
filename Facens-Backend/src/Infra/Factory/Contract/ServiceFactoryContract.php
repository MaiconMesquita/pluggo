<?php

namespace App\Infra\Factory\Contract;

use App\Domain\Entity\Service\BrandedCardUserService\BrandedCardUserService;
use App\Domain\Entity\Service\BrevoService\BrevoRequests;
use App\Domain\Entity\Service\UploadFile\UploadFile;
use App\Infra\ThirdParty\Uuid\Uuid;
use App\Domain\Entity\Service\ValidateDocument\ValidateDocument;
use App\Infra\ThirdParty\Storage\Storage;

interface ServiceFactoryContract
{
    public function getUuid(): Uuid;
    public function getValidateDocument(): ValidateDocument;
    public function getBrevoService(): BrevoRequests;
}
