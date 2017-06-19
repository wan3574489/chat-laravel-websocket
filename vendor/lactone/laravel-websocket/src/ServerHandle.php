<?php

namespace webSocket;

use App\Service\PushService;
use webSocket\Facades\Queue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ServerHandle
{
    /**
     * 系统错误提示
     */
    const SYS_ERROR         = "SysError";

    protected $server = false;
        
    static $channels = [];
    
    public function __construct(WebSocket $webSocket)
    {
        PushService::clean();

        $this->server = $webSocket;
    }



    /**
     * 反馈给client的结果
     * @param $action 
     * @param $status
     * @param $message
     * @return array
     */
    public function results($action,$status,$message = array()){
        return [
            'action'=>$action,
            'status'=>$status,
            'message'=>$message
        ];
    }

    /**
     * 添加任务到Task进程
     * @param $fd
     * @param $key
     * @param array $params
     */
    public function pushTask($fd, $key, $params = []){
        return $this->server->addTask($fd,$key,$params);
    }


    /**
     * 发送消息给某fd
     * @param $fd
     * @param $data
     */
    public function pushToFd($fd,$data){
        $this->server->push($fd,null,$data);
    }

    /**
     * 发送消息给所有的fd
     * @param $data
     */
    public function pushToAll($data){
        $this->server->pushAll($data);
    }

    /**
     * 发送消息给所有的fd，不包括$fd
     * @param $fd
     * @param $data
     */
    public function pushToAllOutMe($fd,$data){
        $this->server->pushToAllOutMe($fd,$data);
    }

    /**
     * 调试模式下调试发布与订阅机制
     * @param $channel
     * @param $data
     */
    protected function call($channel,$data){
        foreach(self::$channels as $c => $class){
            if($c == $channel){
                /**
                 * @var $Object RedisSubscribe
                 */
                $Object = new $class($data);
                $Object->handle();
            }
        }
    }

    /**
     * 发布消息
     * @param $channel
     * @param string $data
     */
    protected function publish($channel,$data = ''){
        if(env('APP_ENV') =='local'){
            $this->call($channel,$data);
        }else{
            Queue::push($channel,$data);
        }
    }

    /**
     * message分发
     * @param $fd
     * @param $data
     * @return mixed
     */
    public function message($fd,$data){
        if(isset($data['type'])){
            $key = "message_".$data['type'];
            if(method_exists($this,$key)){
                return $this->$key($fd,$data);
            }
        }

        Log::error("message no run ",$data);
    }

    /**
     * 任务分发
     * @param $task
     * @param $fd
     * @param $data
     * @return array
     */
    public function task($task,$fd,$data){
        $key = "task_".$task;
        if(method_exists($this,$key)){
            return $this->$task($fd,$data);
        }else{
            return $this->results(self::SYS_ERROR,0,'无处理程序');
        }
    }

    public function openBefore($fd){

    }

    /**
     * 数据监听
     * @param $fd
     */
    public function timer($fd){
        $the = $this;

        \swoole_timer_after(200,function () use ($fd,$the){
            try{
                Log::info("{$fd}开始消费！");
                while($value = Queue::lpop(PushService::getFdChannel($fd))){
                    $this->pushToFd( $fd, $value );
                }

                $the->timer($fd);

            }catch (\Exception $e){

                Log::info("timer error:".$e->getMessage());

                $this->triggerClose($fd);
            }
        });

    }

    public function open($fd){

        $this->timer($fd);

    }

    /**
     * 打开链接之后
     * @param $fd
     */
    public function openAfter($fd){
        Log::info(" {$fd} is open! ");

        PushService::login($fd);

        $length = Redis::get(PushService::Store_fds_length);
        Log::info("当前 fds length {$length}");
    }

    public function finish($task_id,$data){

    }

    public function closeBefore($fd){

    }

    public function close($fd){

    }

    /**
     * 打开链接之后
     * @param $fd
     */
    public function closeAfter($fd){
        log:info($fd." is close !");

        PushService::out($fd);
    }

    /**
     * 触发关闭事件
     * @param $fd
     */
    public function triggerClose($fd){
        $this->openBefore($fd);

        $this->close($fd);

        $this->closeAfter($fd);
    }
}