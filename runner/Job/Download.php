<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 11:22
 */

namespace runner\Job;

use Downloader\Url;
use Runner\Job;

class Download extends Job
{
    public function run()
    {
        $downloadQueue = $this->runner->queueManager->getQueue('download');
        while ($task = $downloadQueue->pop()) {
            $url = Url::fromJson($task);
            break;
        }
    }
}
