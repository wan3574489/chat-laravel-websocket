<?php
/**
 * Created by PhpStorm.
 * User: pz
 * Date: 2017/6/11
 * Time: 21:37
 */

namespace App\Console\Commands;

use App\Server\Packet\Subscribe\PacketRobSubscribe;
use webSocket\RedisSubscribeCommand;

class PacketRobSubscribeCommand extends RedisSubscribeCommand
{
     protected $signature = 'Chat:PacketRob';

     protected $description = 'PacketRobSubscribeCommand';
     
     protected $channel = 'packet.rob';

     protected $handler = PacketRobSubscribe::class;
}