<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:25
 */

namespace Runner\Job;

use Downloader\DataException;
use Downloader\UrlReader;
use Runner\Job;
use Runner\UsageException;

class Schedule extends Job
{
    public function run()
    {
        if (!isset($this->argv[2])) {
            throw new UsageException('No file to schedule supplied');
        }

        try {
            $reader = new UrlReader($this->argv[2]);
        } catch (DataException $e) {
            throw new UsageException($e->getMessage(), $e->getCode(), $e);
        }

        $queue = $this->runner->queueManager->getQueue('download');
        $failedQueue = $this->runner->queueManager->getQueue('fail');
        while ($url = $reader->get()) {
            if ($url->isValid()) {
                $queue->push($url);
            } else {
                $failedQueue->push($url);
            }
        }
    }
}
