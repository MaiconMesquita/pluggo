<?php

namespace App\Infra\Controller;

use App\Domain\Exception\{
    AccessDeniedByHierarchyException,
    AccountLockedByTooManyAttemptsException,
    BlockingByScoreException,
    DeviceNotFoundException,
    IncompleteRegistrationException,
    InvalidDataException,
    InvalidAuthException,
    NotAcceptableException,
    NotFoundException,
    PartnerException,
    ThirdException
};
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;
use App\Infra\ThirdParty\Logging\Logging;

use DomainException;
use Exception;


class Handler implements Controller
{

    private Logging $logging;
    private string $requestId;

    public function __construct(
        private Controller $controller,
        private ThirdPartyFactoryContract $thirdParty
    ) {
        if (!empty($_SERVER['AWS_LAMBDA_LOG_STREAM_NAME']))
            $this->requestId = base64_encode($_SERVER['AWS_LAMBDA_LOG_STREAM_NAME'] . ':' . json_decode($_SERVER['LAMBDA_INVOCATION_CONTEXT'], true)['awsRequestId']);
        else
            $this->requestId = '';
        $this->logging = $thirdParty->getLogging();
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        try {
            $httpResponse = $this->controller->handle($httpRequest);
        } catch (InvalidDataException | DomainException $ide) {
            $httpResponse = $this->handle400HTTP($ide->getMessage());
            $this->logging->warning($ide->getMessage());
        } catch (InvalidAuthException $iae) {
            $this->logging->warning($iae->getMessage());
            $httpResponse = $this->handle401HTTP();
        } catch (IncompleteRegistrationException $ire) {
            $this->logging->warning($ire->getMessage());
            $httpResponse = $this->handle402HTTP($ire->getMessage());
        } catch (NotFoundException $nfe) {
            $this->logging->warning($nfe->getMessage());
            $httpResponse = $this->handle404HTTP($nfe->getMessage());
        } catch (DeviceNotFoundException $dnfe) {
            $this->logging->warning($dnfe->getMessage());
            $httpResponse = $this->handle405HTTP($dnfe->getMessage());
        } catch (NotAcceptableException $nafe) {
            $this->logging->warning($nafe->getMessage());
            $httpResponse = $this->handle406HTTP($nafe->getMessage());
        } catch (AccessDeniedByHierarchyException $adhe) {
            $this->logging->warning($adhe->getMessage());
            $httpResponse = $this->handle407HTTP($adhe->getMessage());
        } catch (AccountLockedByTooManyAttemptsException $altmae) {
            $this->logging->warning($altmae->getMessage());
            $httpResponse = $this->handle408HTTP($altmae->getMessage());
        } catch (BlockingByScoreException $bde) {
            $this->logging->warning($bde->getMessage());
            $httpResponse = $this->handle409HTTP($bde->getMessage());
        } catch (PartnerException $partnerException) {
            $this->logging->warning($partnerException->getMessage());
            $httpResponse = $this->handle502HTTP($partnerException->getMessage());
        } catch (ThirdException $thirdException) {
            $this->logging->warning($thirdException->getMessage());
            $httpResponse = $this->handleThirdHTTP($thirdException);
        } catch (Exception | \ErrorException | \RuntimeException | \Throwable $e) {
            $this->logging->error($e->getMessage());
            $this->logging->error(json_encode($e->getTrace()));
            $httpResponse = $this->handle500HTTP($e, false);
        }

        $this->logging->info('[response] ' . json_encode($httpResponse->body));
        return $httpResponse;
    }

    private function handle400HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_BAD_REQUEST, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    private function handle402HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_INCOMPLETE_REGISTRATION, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    public function handle401HTTP(): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_ACCESS_FORBIDDEN, [
            'requestId' => $this->requestId,
            'message' => 'Access forbidden'
        ]);
    }

    public function handle403HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_ACCESS_DENIED, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    private function handle404HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_NOT_FOUND_CODE, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    private function handle405HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_DEVICE_NOT_FOUND, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    private function handle406HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_NOT_ACCEPTABLE, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    private function handle407HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_ACCESS_DENIED_BY_HIERARCHY_EXCEPTION, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    private function handle408HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_ACCOUNT_LOCKED_BY_TOO_MANY_ATTEMPTS_EXCEPTION, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }

    public function handle409HTTP(string $message): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_BLOCKING_BY_SCORE_EXCEPTION,
            [
                'requestId' => $this->requestId,
                'message' => $message
            ]
        );
    }

    public function handleThirdHTTP(ThirdException $status): HttpResponse
    {
        return new HttpResponse(
            $status->getStatusCode(),
            [
                'requestId' => $this->requestId,
                'message' => $status->getMessage()
            ]
        );
    }

    private function handle500HTTP($exception, $jsonErrorMessage = false): HttpResponse
    {
        $message = "- Tipo de Exception: " . $exception::class;
        $message .= "\n- Erro: " . $exception->getMessage();
        $message .= "\n- Stacktrace: \n" . json_encode($exception->getTrace());
        return new HttpResponse(
            HttpResponse::HTTP_INTERNAL_ERROR_CODE,
            [
                'requestId' => $this->requestId,
                'message' => $_ENV['ENV'] !== 'prod' ? $message : 'Server internal error, please contact the support'
            ]
        );
    }

    private function handle502HTTP($message): HttpResponse
    {
        return new HttpResponse(HttpResponse::HTTP_BAD_GATEWAY, [
            'requestId' => $this->requestId,
            'message' => $message
        ]);
    }
}
