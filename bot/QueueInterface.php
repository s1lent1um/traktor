<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 1:04
 */

namespace Bot;

interface QueueInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function push($data);

    /**
     * @param int $blocking
     * @return string
     */
    public function pop($blocking = 0);
}
