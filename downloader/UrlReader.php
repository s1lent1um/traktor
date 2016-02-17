<?php
/**
 * Created by silentium
 * Date: 17.02.16
 * Time: 9:41
 */

namespace Downloader;

class UrlReader
{
    /** @var resource opened file stream */
    protected $fp;

    public function __construct($filename)
    {
        if (!is_readable($filename)) {
            throw new DataException("File '$filename' does not exit or access is denied");
        }
        $this->fp = fopen($filename, 'r');
    }

    public function __destruct()
    {
        $this->close();
    }

    public function get()
    {
        if (!$this->fp || feof($this->fp)) {
            $this->close();
            return false;
        }
        return new Url(trim(fgets($this->fp)));
    }

    public function close()
    {
        if ($this->fp) {
            fclose($this->fp);
            $this->fp = null;
        }
    }
}
