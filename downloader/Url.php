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
    public $extension;
    public $error;

    public function __construct($url, $fromSerialized = false)
    {
        if ($fromSerialized) {
            $this->url = $url['url'];
            $this->domain = $url['domain'];
            $this->validated = $url['validated'];
            $this->extension = $url['extension'];
            $this->error = $url['error'];
        } else {
            $this->url = $url;
            $this->validatePattern();
            $this->validateDomain();
            $this->validateExtension();
        }
    }

    /**
     * @param $json
     * @return Url
     */
    public static function fromJson($json)
    {
        return new self(json_decode($json, true), true);
    }

    public function validatePattern()
    {
        $this->validated = (bool)preg_match(
            '@^https?://(?:(?:\d{1,3}\.){3}\d{1,3}|((?:\w+\.)+[a-zA-Z]{2,5}))(?:/|$)@',
            $this->url,
            $m
        );
        if (isset($m[1])) {
            $this->domain = $m[1];
        }
        if (!$this->validated) {
            $this->error = 'Malformed Url';
        }
    }

    public function validateDomain()
    {
        if ($this->domain) {
            $this->validated = checkdnsrr($this->domain, 'A');
            if (!$this->validated) {
                $this->error = 'Invalid domain name';
            }
        }
    }

    public function validateExtension()
    {
        if (preg_match('@(\.[a-z0-9]{1,5})$@i', $this->url, $m)) {
            $this->extension = $m[1];
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
            'domain' => $this->domain,
            'validated' => $this->validated,
            'extension' => $this->extension,
            'error' => $this->error,
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
