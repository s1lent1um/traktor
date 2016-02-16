<?php
/**
 * Created by silentium
 * Date: 16.02.16
 * Time: 23:21
 */

namespace Runner;

use \Bot\QueueManager;

class Runner
{
    /** @var  QueueManager */
    protected $queue;
    protected $defaultJob = 'help';

    protected function init()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $this->queue = new QueueManager($redis);
    }

    public function handleException(\Exception $exception)
    {
        // TODO: show exception data
    }

    /**
     * @param $argv
     * @return string
     */
    protected function getJobClass($argv)
    {
        if (!empty($argv[1])
            && ($job = __NAMESPACE__ . '\\Job\\' . Helper::id2camel($argv[1]))
            && class_exists($job)
            && is_a($job, __NAMESPACE__ . '\\Job', true)) {
            return $job;
        }
        return __NAMESPACE__ . '\\Job\\' . Helper::id2camel($this->defaultJob);
    }

    /**
     * @param $argv
     * @return Job
     */
    protected function getJob($argv)
    {
        $className = $this->getJobClass($argv);
        return new $className();
    }

    public function run()
    {
        try {
            $this->init();

            $job = $this->getJob($_SERVER['argv']);
            var_dump($job);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
