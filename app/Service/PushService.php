<?php


namespace App\Service;

use Illuminate\Support\Facades\Redis;
use webSocket\Facades\Queue;

class PushService
{
    /**
     * 存储当前在线的fds
     */
    const Store_fds       = 'redis.set.fds';

    /**
     * 存储当前fds的长度
     */
    const Store_fds_length  = 'redis.key.fds.length';

    /**
     *   清空缓存数据
     */
    static public function clean(){
        if($values  = Redis::sscan(self::Store_fds,1)){
            foreach( $values as $key){
                Redis::srem(self::Store_fds,$key);
            }
        }

        Redis::set(self::Store_fds_length,0);
    }

    /**
     * push
     * @param $fd
     * @param $data
     */
    static public function pushToFdAsync($fd,$data){
        Queue::push(self::getFdChannel($fd),$data);
    }

    /**
     * 发送消息给所有的fd
     * @param $data
     */
    static public function pushToAllAsync($data){
        if($fds = self::getAllFdsFromStore()){
            foreach($fds as $fd){
                self::pushToFdAsync($fd,$data);
            }
        }
    }

    /**
     * 发送消息给所有的fd，不包括$fd
     * @param $fd
     * @param $data
     */
    static public function pushToAllOutMeAsync($fd,$data){
        if($fds = self::getAllFdsFromStore()){
            foreach($fds as $i){
                if($i != $fd){
                    self::pushToFdAsync($i,$data);
                }
            }
        }
    }

    /**
     * 获取从Redis得到的激活的连接数
     * @return mixed
     */
    static public function getAllFdsFromStore(){
        return Redis::smembers(self::Store_fds);
    }

    /**
     * 获取当前用户的channel
     * @param $fd
     * @return string
     */
    static public function getFdChannel($fd){
        return "webSocket.fd.".$fd;
    }
}