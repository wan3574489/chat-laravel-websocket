<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chat</title>
    <style>
        .content{
            width: 1000px;
            margin: 0 auto;
        }

        .rooms{

        }
        .rooms li{

        }
    </style>

    </head>
    <body>
       <div class="content">
           {{--<div class="rooms">
               <ul>
                   <li data-room="1">春</li>
                   <li data-room="2">夏</li>
                   <li data-room="3">秋</li>
                   <li data-room="4">冬</li>
               </ul>
           </div>--}}
           <div class="chat-line">
               <div class="message">
                    1
               </div>
           </div>
           <div class="chat-line">
               <div class="message">
                    2
               </div>
           </div>
       </div>
        <div class=""><input type="text"><button>提交</button></div>
    </body>
<script src="/js/chat.app.js"></script>
</html>
