<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 11:32
 */

namespace Downloader;

class CurlRequest
{
    protected static $defaultOptions = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
    ];

    protected $handler;
    protected $result;
    protected $info;
    protected $error;
    protected $errno;

    public function __construct($url, $options = [])
    {
        $this->handler = curl_init($url);
        curl_setopt_array($this->handler, $options + static::$defaultOptions);
    }

    public function exec()
    {
        $this->result = curl_exec($this->handler);
        $this->info = curl_getinfo($this->handler);
        $this->errno = curl_errno($this->handler);
        $this->error = curl_error($this->handler);
        curl_close($this->handler);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrno()
    {
        return $this->errno;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->info['content_type'];
    }
    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->info['http_code'];
    }
}
