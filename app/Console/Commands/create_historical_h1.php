<?php

namespace App\Console\Commands;

use App\Models\CryptoCurrency;
use Illuminate\Console\Command;

class create_historical_h1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create_historical_h1';

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
        $allCryptoCurrencies = CryptoCurrency::all();

        foreach ($allCryptoCurrencies as $cryptoCurrency) {

            $percentChange = null;
            if(!empty($cryptoCurrency->last_record_h1)) {
                $last_record_h1 = $cryptoCurrency->lastRecordH1;
                $oldPrice = $last_record_h1->price;
                $newPrice = $cryptoCurrency->lastRecordM1->price;

                if($oldPrice > 0 && $newPrice > 0) {
                    $priceChange = $newPrice - $oldPrice;
                    $percentChange = ($priceChange / $oldPrice) * 100;
                }

            }

            if(!empty($cryptoCurrency['last_record_m1'])) {
                $cryptoCurrency->update([
                    'last_record_h1' => $cryptoCurrency['last_record_m1'],
                    'last_change_percent_h1' => $percentChange
                ]);
            }
        }
    }
}
