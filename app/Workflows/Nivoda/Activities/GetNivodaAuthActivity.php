<?php

namespace App\Workflows\Nivoda\Activities;

use App\Http\Integrations\Nivoda\NivodaConnector;
use Workflow\Activity;

class GetNivodaAuthActivity extends Activity
{
    /**
     * @var int
     */
    public $tries = 1;

    public function execute(NivodaConnector $connector): string
    {
        return $connector->getAccessToken()->serialize();
    }
}
