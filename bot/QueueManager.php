<?php

/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:05
 */
namespace Bot;

class QueueManager
{
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }
}
