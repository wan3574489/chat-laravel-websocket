<?php
namespace App\Console\Commands;

use App\Server\Chat\ChatHandle;
use webSocket\Commands\WebSocketCommand;

class ChatWebSocketCommand extends WebSocketCommand
{
    protected $signature = 'Chat:Start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'chat Server';

    protected function getBindClass()
    {
        return ChatHandle::class;
    }



}