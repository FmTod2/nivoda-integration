<?php

namespace App\Http\Integrations\Nivoda\Requests;

use App\Services\Saloon\Concerns\HasGraphQLBody;
use Saloon\Contracts\Body\HasBody;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Request;

class GetClientCredentialsTokenRequest extends Request implements HasBody
{
    use HasGraphQLBody;

    public function __construct(OAuthConfig $config)
    {
        $this->body()->query(<<<GRAPHQL
        authenticate {
            username_and_password(
                username: "{$config->getClientId()}",
                password: "{$config->getClientSecret()}"
            ) {
                token,
                expires,
            }
        }
        GRAPHQL);
    }
}
