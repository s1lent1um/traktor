<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:37
 */

namespace Runner;

abstract class Job
{
    /** @var  Runner */
    protected $runner;
    /** @var array command line arguments vector */
    protected $argv = [];

    public function __construct(Runner $runner, array $argv = [])
    {
        $this->runner = $runner;
        $this->argv = $argv;
    }

    abstract public function run();
}
