<?php

namespace qredis;

/**
 * 入列
 */
class In
{
    /**
     * 入列操作
     * 异常抛出
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-28
     * @param string $key   队列名称
     * @param array $data   待入列数据
     * @param string $host  Redis服务器地址
     * @param string $auth  Redis服务器密码
     * @return array
     */
    public static function run(string $key, array $data, string $host, string $auth)
    {
        try {
            $redis = RedisInstance::instance($host, $auth);
            return array_map(function ($item) use ($key, $redis) {
                return $redis->lpush($key, $item);
            }, $data);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage);
        }
    }
}
