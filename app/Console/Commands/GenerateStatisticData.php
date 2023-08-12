<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Publisher;
use App\Models\Advertisement;
use Carbon\Carbon;
use App\Actions\EnterDataIntoRedis;

class GenerateStatisticData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-statistic-data {number=1 : integer representing number or entries}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for generating random statistic data in Redis database';


    public function __construct(private EnterDataIntoRedis $enterDataIntoRedis)
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $availablePublisherIds = Publisher::whereHas('advertisements')
            ->pluck('id')
            ->toArray();

        $generatedData = [];
        $publisherAdvertisementIds = [];
        for ($i = 0; $i < $this->argument('number'); $i++) {
            $publisherId = $this->getRandomArrayElement($availablePublisherIds);
            if (!isset($publisherAdvertisementIds[$publisherId])) {
                $publisherAdvertisementIds[$publisherId] = Advertisement::where('publisher_id', $publisherId)
                    ->pluck('id')
                    ->toArray();
            }
            $advertisementId = $this->getRandomArrayElement($publisherAdvertisementIds[$publisherId]);
            $time = Carbon::now()->toIsoString();
            $country = fake()->countryCode;

            $generatedData[] = json_encode([
                "advertisementId" => $advertisementId,
                "publisherId" => $publisherId,
                "country" => $country,
                "time" => $time,
            ]);
        }
        $this->enterDataIntoRedis->execute($generatedData, many: true);
        echo "Done \n";
    }

    public function getRandomArrayElement(array $data)
    {
        //better performance than collect($data)->rand() for large data sets
        return $data[array_rand($data)];
    }
}