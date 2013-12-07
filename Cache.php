<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  tndb
 * @package   Cache.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/tndb/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/tndb
 * @since     $VERSION$
 */

/**
 * Class Cache.
 *
 * @category  tndb
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/tndb/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/tndb
 * @since     $VERSION$
 */
class Cache
{
    function __construct($basePath = null, $expires = 0)
    {
        if (null == $basePath)
        {
            $basePath = dirname(tempnam()) . DIRECTORY_SEPARATOR .
                        md5(__DIR__) . DIRECTORY_SEPARATOR;
        }

        $this->_basePath = $basePath;
        $this->_expires  = 0;
    }


    public function getBasePath($key = '')
    {
        return rtrim($this->_basePath, '\\/') . DIRECTORY_SEPARATOR . $key;
    }


    function getData($key)
    {
        $fileName = $this->getBasePath($key);
        if ($this->hasData($key))
        {
            $content = file_get_contents($fileName);

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
        return filemtime($this->getBasePath($key)) > time() - $this->_expires
               && file_exists($this->getBasePath($key));
    }
}
