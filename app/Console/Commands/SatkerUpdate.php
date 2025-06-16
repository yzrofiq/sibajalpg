<?php

namespace App\Console\Commands;

use App\Models\Satker;
use App\Services\SatkerService;
use Illuminate\Console\Command;

class SatkerUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'satker:update {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update satker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $year   = $this->option('year');
        $satkers    = SatkerService::getMaster($year);
        foreach ($satkers as $item) {
            $payloads   = ['kd_satker' => $item->kd_satker];
            $satker = Satker::firstOrCreate($payloads);
            $item   = (array) $item;
            $satker->update($item);
        }
    }
}
