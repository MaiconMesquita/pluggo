<?php

namespace App\Infra\Factory;

use App\Domain\Entity\Service\BrandedCardUserService\BrandedCardUserService;
use App\Domain\Entity\Service\UploadFile\UploadFile;
use App\Domain\Entity\Service\UploadFile\UploadFromBase64;
use App\Domain\Entity\Service\UploadFile\UploadFromUrl;
use App\Domain\Entity\Service\ValidateDocument\{ValidateDocument, CpfValidate, CnpjValidate};
use App\Helper\CardServiceHelper;
use App\Helper\FileContentsWrapper;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ServiceFactoryContract;
use App\Infra\Partner\BrandedCardUserPartner;
use App\Infra\ThirdParty\Storage\Storage;
use App\Infra\ThirdParty\Uuid\RamseyUuidAdapter;
use App\Infra\ThirdParty\Uuid\Uuid;

/**
 * @codeCoverageIgnore
 */
class ServiceFactory implements ServiceFactoryContract
{

    private ?ValidateDocument $validateDocument = null;
    private ?Uuid $uuid = null;
    private ?UploadFile $uploadFile = null;
    private ?BrandedCardUserService $brandedCardUserService = null;

    public function getValidateDocument(): ValidateDocument
    {
        if (!$this->validateDocument) {
            $this->validateDocument = new CpfValidate();
            $this->validateDocument->setNext(new CnpjValidate());
        }

        return $this->validateDocument;
    }

    public function getUuid(): Uuid
    {
        if (!$this->uuid)
            $this->uuid = new RamseyUuidAdapter();

        return $this->uuid;
    }

    public function getUploadFile(Storage $storage): UploadFile
    {
        if (!$this->uploadFile) {
            $uploadFromUrl = new UploadFromUrl(
                $storage,
                new RamseyUuidAdapter(),
                new FileContentsWrapper(),
                new \finfo(FILEINFO_MIME_TYPE)
            );

            $uploadFromBase64 = new UploadFromBase64(
                new RamseyUuidAdapter(),
                new FileContentsWrapper(),
                $storage
            );

            $uploadFromUrl->setNext($uploadFromBase64);

            $this->uploadFile = $uploadFromUrl;
        }
        return $this->uploadFile;
    }

    public function getBrandedCardUserService(): BrandedCardUserService
    {
        if (!$this->brandedCardUserService) {
            $repositoryFactory = new RepositoryFactoryMySQL(Doctrine::getInstance());
            $thirdPartyFactory = new ThirdPartyFactory();
            $brandedCardUserPartner = new BrandedCardUserPartner(
                cardServiceHelper: new CardServiceHelper(
                    thirdParty: $thirdPartyFactory,
                )
            );
            $this->brandedCardUserService = new BrandedCardUserService(
                repositoryFactory: $repositoryFactory,
                brandedCardUserPartner: $brandedCardUserPartner
            );
        }
        return $this->brandedCardUserService;
    }
}
