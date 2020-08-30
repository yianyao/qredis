<?php

namespace qredis;

/**
 * 出列
 */
class Out
{
    /**
     * 出列操作
     * 从指定key的队列头部出列，并通过回调函数对出列数据进行处理
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-28
     * @param string $key           队列名称
     * @param \Closure $callback    回调函数
     * @param string $host          redis服务器
     * @param string $auth          redis服务器密码
     * @return void
     */
    public function run(string $key, \Closure $callback, string $host, string $auth)
    {
        try {
            $redis = RedisInstance::instance($host, $auth);
            $info  = $redis->lpop($key);
            return $callback($info);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage);
        }
    }
}
