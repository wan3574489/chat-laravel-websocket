<?php

namespace App\Console\Commands;
use App\Server\Packet\PacketHandle;
use webSocket\Commands\WebSocketCommand;

class PacketWebSocketCommand extends WebSocketCommand
{

    protected $signature = 'WebSocket:Start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WebSocket Server';

    protected function getBindClass()
    {
        return PacketHandle::class;
    }

    protected function getConfig(){
        return 'websocket';
    }
}
