<?php

/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:05
 */

namespace Bot;

class RedisQueue implements QueueInterface
{
    /** @var \Redis */
    protected $redis;
    /** @var  string */
    protected $name;

    public function __construct(\Redis $redis, $name)
    {
        $this->redis = $redis;
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function push($data)
    {
        is_scalar($data) or $data = json_encode($data);
        return (bool)$this->redis->rPush($this->name, $data);
    }

    /**
     * @inheritdoc
     */
    public function pop($blocking = 0)
    {
        return $blocking
            ? $this->redis->blPop($this->name)
            : $this->redis->lPop($this->name);
    }
}
