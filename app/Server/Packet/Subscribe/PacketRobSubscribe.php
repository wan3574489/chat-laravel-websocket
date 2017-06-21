<?php
namespace App\Server\Packet\Subscribe;

use webSocket\Service\PushService;
use App\Service\UserService;
use Illuminate\Support\Facades\Log;
use webSocket\RedisSubscribe;

class PacketRobSubscribe extends RedisSubscribe
{
    function handle()
    {
        $UserService = new UserService();
        $user = $UserService->getFdLinkUser($this->fd);
        if($user){
            Log::info(" 获取用户{$this->fd}成功!");
            sleep(rand(1,2));
            PushService::pushToAllOutMeAsync($this->fd,PushService::success('packet.rob',"用户{$user['id']}领取成功"));
        }else{
            Log::info("fd {$this->fd} 获取用户失败!");
        }
        //print_r($this->data);
    }
}