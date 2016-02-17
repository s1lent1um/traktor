<?php

/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:05
 */
namespace Downloader;

class QueueManager
{
    /** @var \Redis */
    protected $redis;
    /** @var QueueInterface[] */
    protected $queues = [];

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param $name
     * @return QueueInterface
     */
    public function getQueue($name)
    {
        if (!isset($this->queues[$name])) {
            $this->queues[$name] = new RedisQueue($this->redis, $name);
        }
        return $this->queues[$name];
    }
}
