<?php
namespace App\Server\Packet\Subscribe;

use webSocket\Server\RedisSubscribe;

class PacketRobSubscribe extends RedisSubscribe
{
    function handle()
    {
        print_r($this->data);
    }
}