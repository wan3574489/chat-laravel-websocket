<?php

namespace App\Server\Packet;

use webSocket\ServerHandle;
use Illuminate\Support\Facades\Log;


class PacketHandle extends ServerHandle
{
    protected $user = [];

    /**
     * 打开一个用户
     * @param $fd
     * @return array
     */
    public function open($fd){

        $this->timer($fd);
    }

    /**
     * @param $fd
     */
    public function timer($fd){
        $the = $this;
        \swoole_timer_after(1000, function() use($the,$fd){
            $the->server->push( $fd ,null, $the->results('timer',1,"当前时间".time()) );

            $the->timer($fd);
        });
    }

    /**
     * 对客户端发送的登录消息进行处理
     * @param $fd
     * @param $data
     * @return array
     */
    public  function message_login($fd,$data){
        if($this->addUser($fd)){
            Log::info('add user success');
            return $this->results("Login",1,[
                'name'=>$this->user[$fd]
            ]);
        }else{
            Log::info('add user fail!');
        }
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
     * 添加一个用户
     * @param $fd
     * @return bool
     */
    public function addUser($fd){
        if(isset($this->user[$fd])){
            return false;
        }
        $this->user[$fd] = rand(1,10000);
        return true;
    }
}