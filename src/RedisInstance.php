<?php

namespace qredis;

class RedisInstance
{
    public $redis = null;

    protected static $instance = null;

    private static $host = null;

    private static $auth = null;

    final private function __construct()
    {
        $this->redis = new \Redis;
        $this->redis->pconnect(static::$host);
        $this->redis->auth(static::$auth);
    }

    final private function __clone()
    {}

    public static function instance(string $host = "127.0.0.1", string $auth = "780716")
    {
        if (\is_null(static::$instance)) {
            static::$host     = $host;
            static::$auth     = $auth;
            static::$instance = new RedisInstance;
        }
        return static::$instance;
    }

    /**
     * 头部出列
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @return mixed 返回第一个元素的值，或者当 key 不存在时返回 nil
     */
    public function lpop(string $key)
    {
        return $this->redis->lpop($key);
    }

    /**
     * 当给定列表内没有任何元素可供弹出的时候， 连接将被阻塞
     * 当给定多个 key 参数时，按参数 key 的先后顺序依次检查各个列表，弹出第一个非空列表的头元素
     * 如果所有给定 key 都不存在或包含空列表，那么 BLPOP 命令将阻塞连接， 直到有另一个客户端对给定的这些 key 的任意一个执行 LPUSH 或 RPUSH 命令为止
     * 一旦有新的数据出现在其中一个列表里，那么这个命令会解除阻塞状态，并且返回 key 和弹出的元素值。
     * 当 BLPOP 命令引起客户端阻塞并且设置了一个非零的超时参数 timeout 的时候， 若经过了指定的 timeout 仍没有出现一个针对某一特定 key 的 push 操作，
     * 则客户端会解除阻塞状态并且返回一个 nil 的多组合值(multi-bulk value)。
     * timeout 参数表示的是一个指定阻塞的最大秒数的整型值。当 timeout 为 0 是表示阻塞时间无限制
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @param integer $timeout
     * @return mixed
     */
    public function blpop(string $key, int $timeout = 0)
    {
        return $this->redis->blpop($key, $timeout);
    }

    /**
     * 头部入列
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @param [type] $value
     * @return int 入列后的list长度
     */
    public function lpush(string $key, ...$values)
    {
        if (count($values) < 0) {
            return;
        }
        return $this->redis->lpush($key, ...$values);
    }

    /**
     * 尾部出列
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @return mixed 最后一个元素的值，或者当 key 不存在的时候返回 nil
     */
    public function rpop(string $key)
    {
        return $this->redis->rpop($key);
    }

    /**
     * rpop的阻塞式版本（多个redis客户端执行出列命令时适用）
     * 在非空list尾部弹出一个元素，会在list无法弹出任何元素时阻塞连接
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @param integer $timeout
     * @return mixed 当没有元素可以被弹出时返回一个 nil 的多批量值，并且 timeout 过期。
     *               当有元素弹出时会返回一个双元素的多批量值，其中第一个元素是弹出元素的 key，第二个元素是 value
     */
    public function brpop(string $key, $timeout = 0)
    {
        return $this->redis->brpop($key, $timeout);
    }

    /**
     * 尾部入列
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @param [type] $value
     * @return int 入列后的list长度
     */

    public function rpush(string $key, ...$values)
    {
        if (count($values) < 0) {
            return;
        }
        return $this->redis->rpush($key, ...$values);
    }

    /**
     * 队列长度
     * @param string $key
     * @return mixed 长度，不存在返回0，键非list返回错误
     */
    public function llen(string $key)
    {
        return $this->redis->llen($key);
    }

    /**
     * 从队列中获取指定返回的元素
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @param integer $start 从0开始
     * @param integer $stop -1表示最后一个
     * @return void
     */
    public function lrange(string $key, int $start, int $stop)
    {
        return $this->redis->lrange($key, $start, $stop);
    }

    /**
     * 将所有指定成员添加到键为key的有序集中
     * 添加时可指定一或多个（分数score/成员member）对
     * 如果指定添加的成员已经是有序集里的成员，则会更改成员的分数（score）并更新到正确的排序位置
     * @param string $key
     * @param double $score
     * @param mixed $value
     * @param string $option 可选参数，在key之后，分数/成员对之前
     * @return int 添加到key中的成员数量，不包括已存在只是更新分数的成员
     */
    public function zAdd(string $key, float $score, $value, string $option = '')
    {
        return $option == '' ? $this->redis->zAdd($key, $score, $value) : $this->redis->zAdd($key, $option, $score, $value);
    }

    /**
     * 获取有序集中指定长度数据
     * @param string $key
     * @param integer $start 从0开始
     * @param integer $stop  -1为最末
     * @param double $score
     * @return array
     */
    public function zRange(string $key, int $start, int $stop, $score = null)
    {
        return $this->redis->zRange($key, $start, $stop, $score);
    }

    /**
     * 返回有序集指定key其分数在min和max之间（包括了min和max）的成员个数
     * 如果分数范围不包括min或max，可在该参数前加(
     * 如 zCount(key,'(1,3))即返回key中分数为2和3的成员个数
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param string $key
     * @param string $min
     * @param string $max
     * @return void
     */
    public function zCount(string $key, string $min, string $max)
    {
        return $this->redis->zCount($key, $min, $max);
    }

    /**
     * 返回指定key的有序集元素个数,key为空或不存在返回0
     * @param string $key
     * @return int
     */
    public function zCard(string $key)
    {
        return $this->redis->zCard($key);
    }

    /**
     * 删除有序集指定键中的成员。当键存在但非有序集数据类型则返回错误
     * 返回的是实际从有序集中删除的成员个数，不包括不存在的成员
     * @param string $key
     * @param mixed $member
     * @return int
     */
    public function zRem(string $key, $member)
    {
        return $this->redis->zRem($this, $member);
    }

    /**
     * 发布消息到频道
     * @param string $channel 频道名
     * @param string $message 消息内容
     * @return int
     */
    public function publish(string $channel, string $message)
    {
        return $this->redis->publish($channel, $message);
    }

    /**
     * 订阅发布到若干频道的消息，并通过回调处理
     *
     * @Author yianyao yianyao@live.cn
     * @DateTime 08-22
     * @param array $channels   频道列表
     * @param [type] $callback  回调函数
     * @return void
     */
    public function subscribe(array $channels, $callback)
    {
        return $this->redis->subscribe($channels, $callback);
    }

}
