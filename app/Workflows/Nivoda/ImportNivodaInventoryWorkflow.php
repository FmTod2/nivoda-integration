<?php

namespace App\Workflows\Nivoda;

use App\Concerns\Workflows\HasProgress;
use App\Workflows\Nivoda\Activities\CreateStoneFromNivodaInventoryActivity;
use App\Workflows\Nivoda\Activities\GetNivodaAuthActivity;
use App\Workflows\Nivoda\Activities\GetNivodaInventoryChunkActivity;
use App\Workflows\Nivoda\Activities\GetNivodaInventoryCountActivity;
use Workflow\ActivityStub;
use Workflow\Workflow;

class ImportNivodaInventoryWorkflow extends Workflow
{
    use HasProgress;

    public function execute(?string $query = null, ?int $pageSize = 50, ?int $limit = null)
    {
        $auth = yield ActivityStub::make(GetNivodaAuthActivity::class);
        $count = yield ActivityStub::make(GetNivodaInventoryCountActivity::class, $auth, $query);

        if ($limit) {
            $count = min($count, $limit);
        }

        $pageCount = (int) ceil($count / $pageSize);
        $this->setTotalProgress($pageCount);

        $stones = [];

        for ($pageCurrent = 0; $pageCurrent < $pageCount; $pageCurrent++) {
            $items = yield ActivityStub::make(GetNivodaInventoryChunkActivity::class, $auth, $pageCurrent * $pageSize, $pageSize, $query);
            $stones[] = yield ActivityStub::make(CreateStoneFromNivodaInventoryActivity::class, $items);

            $this->incrementProgress();
        }

        return array_merge(...$stones);
    }
}
