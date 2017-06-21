<?php

namespace App\Console\Commands;

use webSocket\Service\PushService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RobotChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Chat:Robot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RobotChat';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while(true){
            $date = date("Y-m-d H:i:s");

            PushService::pushToAllAsync([
                'message'=>"[$date]这是机器人自动发出的信息!"
            ]);
            sleep(1);
        }
    }
}
