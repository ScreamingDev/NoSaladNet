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
 * @package   Csv.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/tndb/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/tndb
 * @since     $VERSION$
 */

/**
 * Class Csv.
 *
 * @category  tndb
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/tndb/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/tndb
 * @since     $VERSION$
 */
class Csv
{
    public $fieldMap;

    protected $_delimiter;

    protected $_enclosure;

    protected $_escape;

    protected $_handle;

    protected $_fields;


    function __construct($file, $delimiter = ',', $enclosure = '"', $escape = '\\')
    {
        $this->_file = $file;
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;
        $this->_escape = $escape;
    }


    /**
     * @return mixed
     */
    public function getDelimiter()
    {
        return $this->_delimiter;
    }


    /**
     * @return mixed
     */
    public function getEnclosure()
    {
        return $this->_enclosure;
    }


    /**
     * @return mixed
     */
    public function getEscape()
    {
        return $this->_escape;
    }

    function getFields()
    {
        if (null == $this->_fields || !empty($remap))
        { // $this->_fields is null: create
            $currentPos = ftell($this->getHandle());
            rewind($this->getHandle());

            $this->_fields = $this->_getRow();

            fseek($this->getHandle(), $currentPos);

            foreach ($this->fieldMap as $origin => $new)
            {
                $pos = array_search($origin, $this->_fields);

                if (false !== $pos)
                { // found: change field
                    $this->_fields[$pos] = $new;
                }
            }
        }

        return $this->_fields;
    }


    function open($mode = 'r')
    {
        $this->_handle = fopen($this->getFileName(), $mode);
    }


    /**
     * .
     *
     * @return array
     */
    protected function _getRow()
    {
        return fgetcsv(
            $this->getHandle(),
            null,
            $this->getDelimiter(),
            $this->getEnclosure(),
            $this->getEscape()
        );
    }


    /**
     * .
     *
     * @return mixed
     */
    protected function getFileName()
    {
        return $this->_file;
    }


    /**
     * .
     *
     * @return mixed
     */
    protected function getHandle()
    {
        return $this->_handle;
    }


    public function position($new = null)
    {
        if (null !== $new)
        {
            fseek($this->getHandle(), $new);
        }

        return ftell($this->getHandle());
    }


    /**
     * .
     *
     * @return array
     */
    public function getRow()
    {
        if ($this->position() == 0)
        { // still beginning: throw away head line
            $this->_getRow();
        }

        $data = $this->_getRow();

        if (null === $data || false === $data)
        {
            return false;
        }

        return array_combine($this->getFields(), $data);
    }


    public function getData()
    {
        $data = array();
        while ($row = $this->getRow())
        {
            $data[] = $row;
        }

        return $data;
    }
}
