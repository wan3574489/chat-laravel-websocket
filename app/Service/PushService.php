<?php


namespace App\Service;

use Illuminate\Support\Facades\Redis;

class PushService
{
    /**
     * 发布服务
     * @param $channel
     * @param $data
     */
    static public function push($channel,$data){
        Redis::lpush($channel,is_string($data)?$data:json_encode($data));
    }

    /**
     * 阻塞获取最后一个元素
     * @param $channel
     * @return mixed
     */
    static public function brpop($channel){
        while(true){
            if($value = Redis::brpop($channel, 10)){
                return $value;
            }
        }
    }

    /**
     * 阻塞获取第一个元素
     * @param $channel
     * @return mixed
     */
    static public function blpop($channel){
        while(true){
            if($value = Redis::blpop($channel, 10)){
                return $value;
            }
        }
    }

    /**
     * 非阻塞获取第一个元素
     * @param $channel
     * @return mixed
     */
    static public function lpop($channel){
        return Redis::lpop($channel);
    }


}