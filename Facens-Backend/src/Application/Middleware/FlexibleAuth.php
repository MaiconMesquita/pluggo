<?php

namespace App\Application\Middleware;

use Exception;
use App\Infra\Controller\HttpRequest;
use App\Domain\Entity\Auth;

class FlexibleAuth
{
    private BearerAuth $bearerAuth;
    private ApiKeyAuth $apiKeyAuth;

    public function __construct(BearerAuth $bearerAuth, ApiKeyAuth $apiKeyAuth)
    {
        $this->bearerAuth = $bearerAuth;
        $this->apiKeyAuth = $apiKeyAuth;
    }

    public function execute(HttpRequest $request)
    {
        // 1️⃣ Tenta Bearer primeiro
        $authorization = $request->headers['Authorization'][0] ?? $request->headers['authorization'][0] ?? null;

        if ($authorization && str_starts_with(strtolower($authorization), 'bearer ')) {
            try {
                $this->bearerAuth->execute($request);
                return; // Bearer válido, autenticação concluída
            } catch (Exception $e) {
                throw new Exception('Bearer token inválido: ' . $e->getMessage());
            }
        }

        // 2️⃣ Se não houver Bearer, tenta API Key
        if (!empty($request->headers['Api-Key']) || !empty($request->headers['api-key'])) {
            try {
                $this->apiKeyAuth->execute($request);
                return; // API Key válida, autenticação concluída
            } catch (Exception $e) {
                throw new Exception('API Key inválida: ' . $e->getMessage());
            }
        }

        // 3️⃣ Nenhum método válido encontrado
        throw new Exception('Nenhum método de autenticação fornecido');
    }
}
