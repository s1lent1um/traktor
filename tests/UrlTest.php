<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 10:46
 */

namespace tests;

use Downloader\Url;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    public function testUrl()
    {
        $url = new Url('http://google.com');
        $this->assertTrue($url->isValid());
        $this->assertEquals('google.com', $url->getDomain());

        $url = new Url('https://pp.vk.me/c7002/v7002853/1522b/Gr0bS1iavb8.jpg');
        $this->assertTrue($url->isValid());
        $this->assertEquals('pp.vk.me', $url->getDomain());

        $url = new Url('https://saehdfkajsdhlf.mke/c7002/v7002853/1522b/Gr0bS1iavb8.jpg');
        $this->assertFalse($url->isValid());

        $url = new Url('https://saehdfkajsdhlf.mke/c7002/v7002853/1522b/Gr0bS1iavb8.jpg');
        $this->assertFalse($url->isValid());

        $url = new Url('ftp://pp.vk.me/c7002/v7002853/1522b/Gr0bS1iavb8.jpg');
        $this->assertFalse($url->isValid());
    }
}
