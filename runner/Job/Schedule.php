<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:25
 */

namespace Runner\Job;

use Runner\Job;

class Schedule extends Job
{
    public function run()
    {
        $queue = $this->runner->queueManager->getQueue('download');
        $queue->push('');
    }
}
