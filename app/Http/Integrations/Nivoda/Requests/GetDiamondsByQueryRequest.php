<?php

namespace App\Http\Integrations\Nivoda\Requests;

use App\Http\Integrations\Nivoda\DTO\DiamondOrder;
use App\Services\Saloon\ArgumentSet;
use App\Services\Saloon\Concerns\HasGraphQLBody;
use App\Services\Saloon\FieldSet;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Request;

class GetDiamondsByQueryRequest extends Request implements HasBody
{
    use HasGraphQLBody;

    protected FieldSet $fields;

    protected ArgumentSet $arguments;

    public function __construct(
        FieldSet|array $fields,
        ?string $query = null,
        ?DiamondOrder $order = null,
        ?int $offset = null,
        ?int $limit = null,
    ) {
        $this->fields = $fields instanceof FieldSet ? $fields : new FieldSet($fields);

        $this->arguments = new ArgumentSet([
            'query' => $query,
            'order' => $order,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    public function defaultBody(): string
    {
        return <<<GRAPHQL
        diamonds_by_query $this->arguments {
            {$this->fields}
        }
        GRAPHQL;
    }
}
