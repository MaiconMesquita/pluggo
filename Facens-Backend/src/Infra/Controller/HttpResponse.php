<?php

namespace App\Infra\Controller;

final class HttpResponse
{

    const HTTP_SUCCESS_CODE = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED_BUT_PROCESSING_CODE = 202;
    const HTTP_NO_CONTENT = 204;    
    const HTTP_BAD_REQUEST = 400;
    const HTTP_ACCESS_FORBIDDEN = 401;
    const HTTP_INCOMPLETE_REGISTRATION = 402;
    const HTTP_ACCESS_DENIED = 403;
    const HTTP_NOT_FOUND_CODE = 404;
    const HTTP_DEVICE_NOT_FOUND = 405;    
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_ACCESS_DENIED_BY_HIERARCHY_EXCEPTION = 407;
    const HTTP_ACCOUNT_LOCKED_BY_TOO_MANY_ATTEMPTS_EXCEPTION = 408;
    const HTTP_BLOCKING_BY_SCORE_EXCEPTION = 409;
    const HTTP_INTERNAL_ERROR_CODE = 500;
    const HTTP_BAD_GATEWAY = 502;    

    public function __construct(
        public int $statusCode,
        public $body,
        public string|null $templateName = null
    ) {
    }
}
