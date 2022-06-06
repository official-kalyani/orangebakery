<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Coinexpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coin Expiry';

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
     * @return mixed
     */
    public function handle()
    {
        $date = today()->format('Y-m-d');
        $cbcoins = Cbcoin::where('expiry_at', '<=', $date)->get();
        foreach ($cbcoins as $cbcoin) {
            Cbcoin::where('id', $cbcoin->id)->update([
            'status' => 0
            ]);
        }
    }
}
