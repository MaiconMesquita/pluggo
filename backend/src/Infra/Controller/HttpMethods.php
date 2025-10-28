<?php

namespace App\Infra\Controller;

enum HttpMethods: string
{
    case POST = 'post';
    case GET = 'get';
    case OPTIONS = 'options';
    case DELETE = 'delete';
    case PUT = 'put';
}
