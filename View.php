<?php

namespace NoSaladNet;

class View
{
    protected $_file;
    protected $_data = array();

    public function __construct($file)
    {
        if (!stream_resolve_include_path($file))
        {
            throw new InvalidArgumentException(
                sprintf('File "%s" was not found.', $file)
            );
        }

        $this->_file = $file;
    }

    public function setData($key, $value)
    {
        $this->_data[$key] = $value;
    }

    public function getData($key)
    {
        if (!isset($this->_data[$key]))
        {
            return null;
        }

        return $this->_data[$key];
    }

    public function __invoke()
    {
        $this->dispatch();
    }

    public function dispatch()
    {
        echo $this->render();
    }

    public function render()
    {
        ob_start();
        extract($this->_data, EXTR_OVERWRITE);
        include $this->getFile();

        return ob_get_clean();
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->_file;
    }
}
