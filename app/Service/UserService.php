<?php

namespace App\Service;

use App\User;
use Illuminate\Support\Facades\Redis;

class UserService
{

    /**
     * 用户登录
     * @param $data
     * @return User|bool
     */
    function userLogin($data,$fd){
        if($user = $this->hasUser($data)){
            $this->joinRoom($user,$data);
            $this->fdLinkUser($fd,$user);

            return $user;
        }
        return false;
    }

    /**
     * fd与User关联
     * @param $fd
     * @param User $user
     */
    protected function fdLinkUser($fd,User $user){
        return Redis::set("fd.link.{$fd}",$user->remember_token);
    }

    /**
     * 根据fd获取对应的用户
     * @param $fd
     * @return User|bool
     */
    public function getFdLinkUser($fd){
        $openid = Redis::get("fd.link.{$fd}");
        if($openid){
            if($user = User::getUserFromOpenid($openid)){
                return $user;
            }
        }
        return false;
    }

    /**
     * 加入房间
     * @param $user
     * @param $data
     */
    protected function joinRoom($user,$data){
        $roomService = new RoomService();
        if(isset($data['roomid'])){
            $roomService->joinRoom($user,$data['roomid']);
        }else{
            $roomService->joinRoom($user,$roomService->getFreeRoomId());
        }
    }

    /**
     * 用户是否存在，不存在就添加一个
     * @param $data
     * @return User|bool
     */
    protected function hasUser($data){
        if($user = User::getUserFromOpenid($data['openid'])){
            return $user;
        }
        $userObject = new User();
        $userObject->remember_token = $data['openid'];
        $userObject->name = $data['name'];
        $userObject->email = $data['name']."@abc.com";
        $userObject->password = md5($data['openid']);
        if(!$userObject->save()){
            return false;
        }
        return $userObject;
    }
}