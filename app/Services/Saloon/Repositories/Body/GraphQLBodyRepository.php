<?php

namespace App\Services\Saloon\Repositories\Body;

use App\Services\Saloon\Enums\RequestType;
use Saloon\Contracts\Body\BodyRepository;
use Saloon\Traits\Body\CreatesStreamFromString;
use Saloon\Traits\Conditionable;
use Stringable;

class GraphQLBodyRepository implements BodyRepository, Stringable
{
    use CreatesStreamFromString;
    use Conditionable;

    /**
     * The data in the repository
     */
    protected ?string $data = null;

    /**
     * Root type of the request
     */
    protected RequestType $type = RequestType::QUERY;

    /**
     * JSON encoding flags
     *
     * Use a Bitmask to separate other flags. For example: JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
     */
    protected int $jsonFlags = JSON_THROW_ON_ERROR;

    /**
     * Create a new instance of the repository
     */
    public function __construct(RequestType $type = RequestType::QUERY, string|null $value = null)
    {
        $this->set($value, $type);
    }

    /**
     * Set a value inside the repository
     *
     * @param string|null $value
     * @return $this
     */
    public function set(mixed $value, ?RequestType $type = null): static
    {
        $this->data = $value;

        if ($type) {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * Set data in the repository as a query request
     */
    public function query(string $value): static
    {
        return $this->set($value, RequestType::QUERY);
    }

    /**
     * Set data in the repository as a mutation request
     */
    public function mutation(string $value): static
    {
        return $this->set($value, RequestType::MUTATION);
    }

    /**
     * Set data in the repository as a raw request
     */
    public function raw(string $value): static
    {
        return $this->set($value, RequestType::RAW);
    }

    /**
     * Retrieve all in the repository
     */
    public function all(): ?string
    {
        $content = match($this->type){
            RequestType::RAW => $this->data,
            DEFAULT => "{$this->type->value} {{$this->data}}"
        };

        return <<<GRAPHQL
        {$content}
        GRAPHQL;
    }

    /**
     * Determine if the repository is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Determine if the repository is not empty
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Set the JSON encoding flags
     *
     * Must be a bitmask like: ->setJsonFlags(JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
     *
     * @return $this
     */
    public function setJsonFlags(int $flags): static
    {
        $this->jsonFlags = $flags;

        return $this;
    }

    /**
     * Get the JSON encoding flags
     */
    public function getJsonFlags(): int
    {
        return $this->jsonFlags;
    }

    /**
     * Convert the body repository into a string.
     */
    public function __toString(): string
    {
        $json = json_encode(['query' => $this->all()], $this->getJsonFlags());

        return $json === false ? '' : $json;
    }
}
