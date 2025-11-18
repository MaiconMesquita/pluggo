<?php

namespace App\Infra\Controller;

interface MiddlewareContract
{
    public function execute(HttpRequest $request);
}
