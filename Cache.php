<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  NoSaladNet
 * @package   Cache.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/NoSaladNet/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/NoSaladNet
 * @since     $VERSION$
 */

/**
 * Class Cache.
 *
 * @category  NoSaladNet
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/NoSaladNet/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/NoSaladNet
 * @since     $VERSION$
 */
class Cache
{
    const DAY = 86400;
    const HOUR = 3600;

    function __construct($basePath = null, $expires = 0)
    {
        if (null == $basePath)
        {
            $basePath = dirname(tempnam()) . DIRECTORY_SEPARATOR .
                        md5(__DIR__) . DIRECTORY_SEPARATOR;
        }

        if (!is_readable($basePath))
        {
            mkdir($basePath, 0755, true);
        }

        $this->_basePath = $basePath;
        $this->_expires  = $expires;
    }


    public function getBasePath($key = '')
    {
        return rtrim($this->_basePath, '\\/') . DIRECTORY_SEPARATOR . $key;
    }


    function getData($key)
    {
        if ($this->hasData($key))
        {
            $fileName = $this->getBasePath($key);
            $content  = file_get_contents($fileName);

            return unserialize($content);
        }

        return null;
    }


    public function getExpireTime()
    {
        return $this->_expires;
    }


    public function setData($key, $data)
    {
        file_put_contents($this->getBasePath($key), serialize($data));
    }


    /**
     * .
     *
     * @param $key
     *
     * @return bool
     */
    protected function hasData($key)
    {
        return file_exists($this->getBasePath($key))
               && filemtime($this->getBasePath($key)) > time() - $this->_expires;
    }
}
