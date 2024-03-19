<?php

namespace App\Workflows\Nivoda\Activities;

use App\Actions\ImportNivodaStone;
use Workflow\Activity;

class CreateStoneFromNivodaInventoryActivity extends Activity
{
    /**
     * @var int
     */
    public $tries = 1;

    public function execute(array $items): array
    {
        $stones = [];

        foreach ($items as $data) {
            $stone = ImportNivodaStone::run($data);

            $stones[] = $stone->id;

            $this->heartbeat();
        }

        return $stones;
    }

}
