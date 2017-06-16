<?php

namespace App\Service;

use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RoomService
{
    /**
     * 获取所有房间人数总数
     * @return array
     */
    public function getAllRoomCount(){
        $rooms = $this->getAllRooms();
        foreach ($rooms as $roomId => $roomName){
            $rooms[$roomId] = $this->getRoomCount($roomId);
        }
        return $rooms;
    }

    /**
     * 获取人数最少的房间id
     * @return mixed
     */
    public function getFreeRoomId(){
        $roomCount = $this->getAllRoomCount();
        asort($roomCount);
        list($key, $val) = each($roomCount);
        return $key;
    }

    /**
     * 用户加入到房间中
     * @param User $user
     * @param $room
     * @return bool
     */
    public function joinRoom(User $user,$roomId){
        Redis::sadd("room.{$roomId}.users",$user->remember_token);
        Redis::set("room.user.{$user->remember_token}",$roomId);

        Log::info("{$user->remember_token} join room {$roomId} ");

        return true;
    }

    /**
     * 退出房间
     * @param User $user
     * @return bool
     */
    public function outRoom(User $user){
        $roomId = Redis::get("room.user.{$user->remember_token}");

        Redis::srem("room.{$roomId}.users",$user->remember_token);
        Redis::del("room.user.{$user->remember_token}");

        Log::info("{$user->remember_token} out room {$roomId} ");

        return true;
    }

    /**
     * 获取用户当前所在的房间
     * @param User $user
     * @return mixed
     */
    public function getRoomFromUser(User $user){
        return Redis::get("room.user.{$user->remember_token}");
    }

    /**
     * 获取房间里面用户列表
     * @param $room
     * @return mixed
     */
    public function getRoomUsers($roomId){
        return Redis::smembers("room.{$roomId}.users");
    }

    /**
     * 获取room用户总数
     * @param $room
     * @return mixed
     */
    public function getRoomCount($roomId){
        return Redis::scard("room.{$roomId}.users");
    }

    /**
     * 获取所有的房间
     * @return array
     */
    public function getAllRooms(){
        return [
            1=>1,
            2=>2,
            3=>3,
            4=>4
        ];
    }

}