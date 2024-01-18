<?php

namespace App\Console\Commands;

use App\Models\FarmUser;
use App\Models\Nursery;
use App\Models\SeedlingService;
use App\Models\SeedType;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class linkfarmUserWithNursery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-farmUser-Nursery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $farmUsers = FarmUser::all();
        $nursery = Nursery::find(1);
        foreach ($farmUsers as $farmUser){
            $nursery->farmUsers()->attach($farmUser);
        }
    }
}
