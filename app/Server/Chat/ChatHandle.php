<?php

namespace App\Server\Chat;

use App\Service\PushService;
use App\Service\RoomService;
use App\Service\UserService;
use webSocket\ServerHandle;
use Illuminate\Support\Facades\Log;

class ChatHandle extends ServerHandle
{
    /**
     * 对客户端发送的登录消息进行处理
     * @param $fd
     * @param $data
     * @return array
     */
    public  function message_login($fd,$data){
        $data['openid'] = md5($fd);
        $data['name'] = $fd;

        $UserService = new UserService();

        if($user = $UserService->userLogin($data,$fd)){

            Log::info('add user success');

            //发送消息消息给自己
            $this->pushToFd($fd,$this->results("Login",1,[
                'name' => $data['name'],
                'message'=>'您登陆成功'
            ]));

            //发送消息给所有人
            $this->pushToAllOutMe($fd,$this->results("Login_Other",1,[
                'name'=>$data['name'],
                'message' =>'用户'.$data['name']."登陆成功"
            ]));

        }else{
            Log::info('add user fail!');
        }
    }

    /**
     * 异步消息请求处理
     * @param $fd
     * @param $data
     */
    public function message_Async($fd,$data){

        PushService::pushToAllOutMeAsync($fd,$this->results("async",1,[
            'message' => "Async 任务发送成功2"
        ]));

        /*PushService::pushToAllAsync($this->results("async1",1,[
            'message' => "Async 任务发送成功1"
        ]));*/

    }

    /**
     * 客户端发送订阅事件
     * @param $fd
     * @param $data
     */
    public function message_publish($fd,$data){
        $this->publish("packet.rob",is_string($data)?$data:json_encode($data));
    }

    /**
     * 对客户端的其它消息发送信息
     * @param $fd
     * @param $data
     * @return array
     */
    public function message_chat($fd,$data){
        return $this->results("chat",1,"发送成功");
    }


    /**
     * 用户关闭了连接
     * @param $fd
     */
    public function close($fd){
        log:info($fd." is close !");

        $userSerivice = new UserService();
        if($user = $userSerivice->getFdLinkUser($fd)){
            $RoomService = new RoomService();
            $RoomService->outRoom($user);
        }
    }

}