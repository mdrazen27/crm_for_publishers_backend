<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\Redis;
use App\Models\Statistic;
use Carbon\Carbon;


class MigrateRedisStatisticsIntoMySql extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-redis-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects data from redis and transfers it to the MySql database,
                              after transfer is done it deletes keys from Redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $redisEntries = Redis::smembers(config('redis.statisticSetName'));
        // data to migrate follows structure [publisherId][advertisementId][countryCode]
        $dataToMigrate = [];
        if (!$redisEntries) {
            echo "Nothing to migrate! \n";
            return;
        }
        foreach ($redisEntries as $redisEntry) {
            $entry = json_decode($redisEntry, true);
            $publisherId = $entry['publisherId'];
            $advertisementId = $entry['advertisementId'];
            $country = $entry['country'];

            if (isset($dataToMigrate[$publisherId][$advertisementId][$country])) {
                $dataToMigrate[$publisherId][$advertisementId][$country]['count']++;
            } else {
                $dataToMigrate[$publisherId][$advertisementId][$country] = ['count' => 1, ...$entry];
            }
        }
        foreach ($dataToMigrate as $dataPerPublisher) {
            foreach ($dataPerPublisher as $dataPerAdvertisement) {
                foreach ($dataPerAdvertisement as $dataPerCountry) {
                    $statistics = Statistic::where('publisher_id', $dataPerCountry['publisherId'])
                        ->where('advertisement_id', $dataPerCountry['advertisementId'])
                        ->where('date', Carbon::parse($dataPerCountry['time'])->format('Y-m-d'))
                        ->where('country', $dataPerCountry['country'])
                        ->first();

                    if ($statistics) {
                        $statistics->count += $dataPerCountry['count'];
                        $statistics->save();
                    } else {
                        Statistic::create([
                            'publisher_id' => $dataPerCountry['publisherId'],
                            'advertisement_id' => $dataPerCountry['advertisementId'],
                            'date' => Carbon::parse($dataPerCountry['time'])->format('Y-m-d'),
                            'country' => $dataPerCountry['country'],
                            'count' => $dataPerCountry['count'],
                        ]);
                    }
                }
            }
        }
        Redis::srem(config('redis.statisticSetName'), ...$redisEntries);
    }
}
