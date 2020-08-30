<?php

namespace qredis;

class Subscribe
{
    /**
     * 订阅若干频道的发布消息并对消息进行处理
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-28
     * @param array $channel            频道列表
     * @param array|string $callback    回调函数，接受三个参数：redis实例对象，频道名称，消息
     * @param string $host              redis服务器
     * @param string $auth              redis服务器密码
     * @return void
     */
    public function run(array $channel, \closure $callback, string $host, string $auth)
    {
        try {
            $redis = RedisInstance::instance($host, $auth);
            $redis->subscribe($channel, $callback);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * 回调函数被调用时，会被自动传入的参数为：运行时接收订阅频道的redis实例， 该频道的名称， 以及收到的消息
     * 详细查阅文档https://github.com/phpredis/phpredis#pubsub

     */
    public function cb(Redis $redis, string $channel, string $msg)
    {

    }
}
