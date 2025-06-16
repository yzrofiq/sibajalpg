<?php

namespace App\Console\Commands;

use App\Models\NonTender;
use App\Models\NonTenderSchedule;
use App\Models\Vendor;
use App\Services\NonTenderScheduleService;
use App\Services\NonTenderService;
use Illuminate\Console\Command;

class NonTenderUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nontender:update {--year=} {--filter=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update non tender';

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
        $year       = $this->option('year');
        $filter     = $this->option('filter');
        $all        = true;

        if( $filter == "done" ) {
            $all    = false;
        }

        if( $all ) {
            $this->info("Get All");
            $nontenders    = NonTenderService::getAll($year);
        } else {
            $this->info("Get Done");
            $nontenders    = NonTenderService::getDone($year);
        }
        
        $this->info("Update Non Tender ITEM");

        foreach ($nontenders as $item) {
            $payloads   = [
                'kd_nontender' => $item->kd_nontender,
            ];
            $nontender  = NonTender::firstOrCreate($payloads);
            if( property_exists($item, 'kd_penyedia') ) {
                $payloadVendor  = [
                    'kd_penyedia' => $item->kd_penyedia,
                    'nama_penyedia' => $item->nama_penyedia,
                    'npwp_penyedia' => $item->npwp_penyedia,
                ];

                $vendor     = Vendor::firstOrCreate($payloadVendor);
            }
            $item   = (array) $item;
            
            $nontender->update($item);

        }
        

        $this->info("Update Non Tender SCHEDULE");
        foreach ($nontenders as $item) {
            $item   = (array) $item;
            $schedules  = NonTenderScheduleService::getByCode($item['kd_nontender']);

            if( $schedules AND count($schedules) ) {
                foreach ($schedules as $value) {
                    $schedule   = NonTenderSchedule::firstOrCreate([
                        'kd_nontender' => $value->kd_nontender,
                        'kd_tahapan' => $value->kd_tahapan,
                    ]);
                    $payload    = (array) $item;
                    $payload['nama_tahapan'] = $value->nama_tahapan;
                    $payload['kd_akt'] = $value->kd_akt;
                    $payload['nama_akt'] = $value->nama_akt;
                    $payload['tanggal_awal'] = $value->tanggal_awal;
                    $payload['tanggal_akhir'] = $value->tanggal_akhir;
                    $schedule->update($payload);
                }
            }

            sleep(1);
        }

    }
}
