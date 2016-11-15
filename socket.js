var http=require('http').Server();//开启http服务
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();
redis.subscribe('test-channel');
redis.on('message',function(channel,message){
   message = JSON.parse(message);
   io.emit(channel + ":" + message.event,message.data);
});
http.listen(3000);//