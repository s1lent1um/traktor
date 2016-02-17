<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 1:04
 */

namespace Downloader;

interface QueueInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function push($data);

    /**
     * @return string
     */
    public function pop();

    /**
     * @param $timeout
     * @return string
     */
    public function listen($timeout);
}
