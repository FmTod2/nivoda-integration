<?php

namespace App\Services\Saloon\Concerns;

use App\Services\Saloon\Enums\RequestType;
use App\Services\Saloon\Repositories\Body\GraphQLBodyRepository;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Traits\Body\ChecksForHasBody;

trait HasGraphQLBody
{
    use ChecksForHasBody;

    /**
     * Body Repository
     */
    protected GraphQLBodyRepository $body;

    /**
     * Boot the plugin
     */
    public function bootHasGraphQLBody(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Content-Type', 'application/json');
    }

    /**
     * Resolve the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/';
    }

    /**
     * Get the method of the request.
     */
    public function getMethod(): Method
    {
        return $this->method ?? Method::POST;
    }

    /**
     * Default type for the request
     */
    protected function defaultType(): RequestType
    {
        return RequestType::QUERY;
    }

    /**
     * Default body for the request
     */
    protected function defaultBody(): ?string
    {
        return null;
    }

    /**
     * Retrieve the data repository
     */
    public function body(): GraphQLBodyRepository
    {
        return $this->body ??= new GraphQLBodyRepository($this->defaultType(), $this->defaultBody());
    }
}
