<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class EnterDataIntoRedis
{
    public function execute(array|string $data, bool $many): void
    {
        try {
            if ($many) {
                Redis::sadd(config('redis.statisticSetName'), ...$data);
            } else {
                Redis::sadd(config('redis.statisticSetName'), $data);
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}