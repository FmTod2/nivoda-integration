<?php

namespace App\Http\Integrations\Nivoda\Resources;

use App\Http\Integrations\Nivoda\DTO\DiamondOrder;
use App\Http\Integrations\Nivoda\Requests\GetDiamondsByQueryCountRequest;
use App\Http\Integrations\Nivoda\Requests\GetDiamondsByQueryRequest;
use App\Services\Saloon\FieldSet;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class DiamondResource extends BaseResource
{
    public function all(FieldSet|array $fields, ?string $query = null, ?DiamondOrder $order = null, ?int $offset = null, ?int $limit = null): Response
    {
        return $this->connector->send(new GetDiamondsByQueryRequest($fields, $query, $order, $offset, $limit));
    }

    public function count(?string $query = null, ?DiamondOrder $order = null, ?int $offset = null, ?int $limit = null): Response
    {
        return $this->connector->send(new GetDiamondsByQueryCountRequest($query, $order, $offset, $limit));
    }
}
