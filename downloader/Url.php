<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 10:11
 */

namespace Downloader;

class Url implements \JsonSerializable
{
    protected $url;
    protected $domain;
    protected $validated = false;

    public function __construct($url, $fromSerialized = false)
    {
        $this->url = $url;
        $this->validatePattern();
        $this->validateDomain();
    }

    public function validatePattern()
    {
        $this->validated = (bool)preg_match(
            '@^https?://(?:(?:\d{1,3}\.){3}\d{1,3}|((?:\w+\.)+[a-z]{2,5}))(?:/|$)@',
            $this->url,
            $m
        );
        if (isset($m[1])) {
            $this->domain = $m[1];
        }
    }

    public function validateDomain()
    {
        if ($this->domain) {
            $this->validated = checkdnsrr($this->domain, 'A');
        }
    }


    public function isValid()
    {
        return $this->validated;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'url' => $this->url,
        ];
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
}
