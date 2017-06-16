<?php
/**
 * Created by PhpStorm.
 * User: pz
 * Date: 2017/6/11
 * Time: 21:37
 */

namespace App\Console\Commands;

use App\Server\Packet\Subscribe\PacketRobSubscribe;
use App\Server\RedisSubscribeCommand;

class PacketRobSubscribeCommand extends RedisSubscribeCommand
{
     protected $signature = 'PacketRob:Start';

     protected $description = 'RedisSubscribeCommand';
     
     protected $channel = 'packet.rob';

     protected $handler = PacketRobSubscribe::class;
}