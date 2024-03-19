<?php

namespace App\Workflows\Nivoda\Activities;

use App\Http\Integrations\Nivoda\NivodaConnector;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Workflow\Activity;

class GetNivodaInventoryCountActivity extends Activity
{
    /**
     * @var int
     */
    public $tries = 1;

    public function execute(NivodaConnector $connector, string $auth, ?string $query = null): int
    {
        $authenticator = AccessTokenAuthenticator::unserialize($auth);
        $connector->authenticate($authenticator);
        $response = $connector->diamond()->count($query)->throw();

        return (int) $response->json('data.diamonds_by_query_count', 0);
    }
}
