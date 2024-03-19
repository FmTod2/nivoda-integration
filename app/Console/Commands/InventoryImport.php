<?php

namespace App\Console\Commands;

use App\Workflows\Nivoda\ImportNivodaInventoryWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class InventoryImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:import {source?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import stones from different sources';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->argument('source') === 'nivoda') {
            $workflow = WorkflowStub::make(ImportNivodaInventoryWorkflow::class);

            $workflow->start(<<<GRAPHQL
            {
                returns: true,
                availability: [AVAILABLE],
                sizes: [{ from: 0.30, to: 20 }],
                clarity: [FL, IF, VVS1, VVS2, VS1, VS2, SI1, SI2, SI3, I1],
                color: [D, E, F, G, H, I, J, K, L, FANCY],
                cut: [FR, F, GD, G, VG, EX, ID],
                polish: [FR, F, GD, G, VG, EX, ID],
                symmetry: [FR, F, GD, G, VG, EX, ID],
                return_window_timeline: 30,
            }
            GRAPHQL, 5, 50);
        }

        return self::SUCCESS;
    }
}
