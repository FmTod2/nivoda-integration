<?php

namespace App\Concerns\Workflows;

use Workflow\QueryMethod;
use Workflow\SignalMethod;

trait HasProgress
{
    protected int $totalProgress = 1;

    protected int $currentProgress = 0;

    #[QueryMethod]
    public function getTotalProgress(): int
    {
        return $this->totalProgress;
    }

    #[QueryMethod]
    public function getCurrentProgress(): int
    {
        return $this->currentProgress;
    }

    #[SignalMethod]
    public function setTotalProgress(int $value): void
    {
        $this->totalProgress = $value;
    }

    #[SignalMethod]
    public function setCurrentProgress(int $value): void
    {
        $this->currentProgress = $value;
    }

    protected function incrementProgress(int $value = 1): void
    {
        $this->setCurrentProgress($this->getCurrentProgress() + $value);
    }
}
