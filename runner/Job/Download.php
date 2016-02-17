<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 11:22
 */

namespace runner\Job;

use Downloader\CurlRequest;
use Downloader\DataException;
use Downloader\Url;
use Runner\Job;

class Download extends Job
{
    public function run()
    {
        $downloadQueue = $this->runner->queueManager->getQueue('download');
        $failedQueue = $this->runner->queueManager->getQueue('fail');
        $doneQueue = $this->runner->queueManager->getQueue('done');
        $tmp = sys_get_temp_dir();
        while ($task = $downloadQueue->pop()) {
            $url = Url::fromJson($task);
            $curl = (new CurlRequest($url->getUrl()))->exec();

            if ($curl->getHttpCode() !== 200) {
                if ($curl->getErrno()) {
                    $url->error = $curl->getError();
                } else {
                    $url->error = "http status {$curl->getHttpCode()}";
                }
                $failedQueue->push($url);
                continue;
            }
            if (!preg_match('@^image/(.+)$@', $curl->getContentType(), $m)) {
                $url->error = 'Not an image';
                $failedQueue->push($url);
                continue;
            }

            $this->saveFile($curl, $url->extension ? false : $m[1], $tmp);
            $doneQueue->push($url);
            break;
        }
    }

    protected function saveFile(CurlRequest $curl, $extension, $path = null)
    {
        $path or $path = sys_get_temp_dir();
        $url = $curl->getInfo()['url'];
        if (!preg_match('@^https?://(.+)$@', $url, $m)) {
            throw new DataException('Bad CurlRequest object supplied');
        }
        $filename = microtime(true) . str_replace(['/', '?'], '.', $m[1]) . ($extension ?: '');
        file_put_contents("$path/$filename", $filename);
    }
}
