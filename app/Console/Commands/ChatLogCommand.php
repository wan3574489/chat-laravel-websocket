<?php

namespace App\Console\Commands;

use App\Service\PushService;
use Illuminate\Console\Command;

class ChatLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Chat:Log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat Log';

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
        while(1){
            $time = time();
            //全部用户
            if($fds = PushService::getAllFdsFromStore()){
                $this->info("[$time]当前用户:".json_encode($fds));
            }else{
                $this->info("[$time]当前没有用户登录");
            }

            sleep(1);
        }
    }
}
