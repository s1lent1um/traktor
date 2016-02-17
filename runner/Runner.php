<?php
/**
 * Created by silentium
 * Date: 16.02.16
 * Time: 23:21
 */

namespace Runner;

use \Downloader\QueueManager;

class Runner
{
    /** @var  QueueManager */
    public $queueManager;

    protected $defaultJob = 'help';

    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    protected function init()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $this->queueManager = new QueueManager($redis);
    }

    public function handleException(\Exception $exception)
    {
        $code = $exception->getCode() ? "[{$exception->getCode()}]" : '';

        echo "\033[90m" . get_class($exception) . "{$code}:\033[0m {$exception->getMessage()}\n";
        if ($exception instanceof UsageException) {
            $this->getJob([])->run();
        } else {
            echo "in {$exception->getFile()}:{$exception->getLine()}\nTrace:\n";
            $trace = array_merge($exception->getTrace(), ['{main}']);
            foreach ($trace as $k => $step) {
                if (is_array($step)) {
                    $function = isset($step['class'])
                        ? $step['class'] . $step['type'] . $step['function']
                        : $step['function'];
                    echo "  #{$k} {$step['file']}[{$step['line']}]: {$function}()\n";
                } else {
                    echo "  #{$k} $step\n";
                }
            }
        }
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
        return new $className($this, $argv);
    }

    public function run($argv)
    {
        try {
            $this->init();

            $this->getJob($argv)->run();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
