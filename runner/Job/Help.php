<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 0:45
 */

namespace Runner\Job;

use Runner\Job;

class Help extends Job
{
    public function run()
    {
        echo <<<TEXT
Usage:
  bot [options] [arguments]

Available commands:
  schedule        Schedule urls from file to download
  download        Download scheduled urls
  help            Show this help


TEXT;

    }
}
