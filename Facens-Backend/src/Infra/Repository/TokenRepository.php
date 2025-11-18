<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Token;
use App\Domain\Entity\ValueObject\TokenType;
use App\Domain\RepositoryContract\TokenRepositoryContract;
use App\Infra\Database\EntitiesOrm\{
    Token as TokenOrm,
};
use Doctrine\ORM\EntityManagerInterface;

class TokenRepository extends BaseRepository implements TokenRepositoryContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $entityManager,
            $entityManager->getRepository(TokenOrm::class)
        );
    }

    public function create(Token $token): Token
    {
        $tokenOrm = new TokenOrm();

        $tokenOrm->id = $token->id;

        if (!empty($token->getDriverId())) $tokenOrm->driverId = $token->getDriverId();
        if (!empty($token->getHostId())) $tokenOrm->hostId = $token->getHostId();

        $tokenOrm->type = $token->getTokenType()->getType();        

        return $this->persist($tokenOrm)->toDomain();
    }

    public function getAndDeleteToken(string $tokenId): bool
    {
        try {
            /** @var TokenOrm */
            $token = $this->getOneByParams(
                [
                    "id" => $tokenId,
                ]
            );

            if ($token) {
                $this->entityManager->remove($token); // Remove o token
                $this->entityManager->flush(); // Aplica a exclusão no banco de dados
                return true; // Retorna true se o token foi encontrado e excluído
            }

            return false; // Retorna false se o token não for encontrado
        } catch (\Throwable $e) {
            return false; // Retorna false se ocorrer um erro
        }
    }


    public function revokeTokenByUserId(int $id, bool $realUser): void
    {
        // Busca todos os tokens associados ao userId
        $tokens = $this->repository->findBy([$realUser ? 'driverId' : 'hostId' => $id]);
        
        // Verifica se algum token foi encontrado
        if ($tokens) {
            foreach ($tokens as $token) {
                $this->entityManager->remove($token); // Remove o token do banco de dados
            }
            $this->entityManager->flush(); // Aplica a exclusão no banco de dados
        }
    }
}
