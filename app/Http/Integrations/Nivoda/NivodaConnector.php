<?php

namespace App\Http\Integrations\Nivoda;

use App\Http\Integrations\Nivoda\Requests\GetClientCredentialsTokenRequest;
use App\Http\Integrations\Nivoda\Resources\DiamondResource;
use DateTimeImmutable;
use Saloon\Contracts\OAuthAuthenticator;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\OAuth2\ClientCredentialsGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class NivodaConnector extends Connector
{
    use ClientCredentialsGrant;
    use AcceptsJson;

    public function __construct(?string $clientId = null, ?string $clientSecret = null)
    {
        if ($clientId) {
            $this->oauthConfig()->setClientId($clientId);
        }

        if ($clientSecret) {
            $this->oauthConfig()->setClientSecret($clientSecret);
        }
    }

    public function resolveBaseUrl(): string
    {
        return config('services.nivoda.api_url');
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId(config('services.nivoda.client_id'))
            ->setClientSecret(config('services.nivoda.client_secret'));
    }

    protected function createOAuthAuthenticatorFromResponse(Response $response): OAuthAuthenticator
    {
        $accessToken = $response->json('data.authenticate.username_and_password.token');
        $expiresIn = $response->json('data.authenticate.username_and_password.expires');

        $expiresAt = new DateTimeImmutable('+' . $expiresIn . ' seconds');

        return $this->createOAuthAuthenticator($accessToken, $expiresAt);
    }

    protected function resolveAccessTokenRequest(OAuthConfig $oauthConfig): GetClientCredentialsTokenRequest
    {
        return (new GetClientCredentialsTokenRequest($oauthConfig));
    }

    public function diamond(): DiamondResource
    {
        return new DiamondResource($this);
    }

    public static function withAuthentication(?string $clientId = null, ?string $clientSecret = null): self
    {
        $instance = new static($clientId, $clientSecret);

        $authenticator = $instance->getAccessToken();

        return $instance->authenticate($authenticator);
    }
}
