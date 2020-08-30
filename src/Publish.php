<?php

namespace qredis;

class Publish
{
    /**
     * 发布消息到指定频道中
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-28
     * @param string $channel   频道名称
     * @param string $message   消息
     * @param string $host      redis服务器
     * @param string $auth      redis密码
     * @return void
     */
    public function run(string $channel, string $message, string $host, string $auth)
    {
        try {
            $redis = RedisInstance::instance($host, $auth);
            return $redis->publish($channel, $message);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
