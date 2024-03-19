<?php

namespace App\Services\Saloon\Enums;

enum RequestType: string
{
    case QUERY = 'query';
    case MUTATION = 'mutation';
    case RAW = 'raw';
}
