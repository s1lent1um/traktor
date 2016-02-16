<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:37
 */

namespace Runner;

class Job
{
    /** @var  Runner */
    protected $runner;

    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
    }

    public function run()
    {

    }
}
