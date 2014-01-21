<?php
/**
 * Contains class to create HTML by using PHP like you would do with jQuery.
 *
 * PHP version 5
 *
 * Copyright (c) 2014, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  NoSaladNet
 * @package   pQuery.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2014 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/NoSaladNet/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/NoSaladNet
 * @since     $VERSION$
 */

/**
 * Create HTML almost like in jQuery with PHP.
 *
 *
 *
 * $pForm = new pQuery('form');
 *
 *
 *  $pForm
 *      ->method('POST')
 *      ->action('?foo')
 *
 *      ->append("Enter your <b>name</b>:")
 *
 *      ->append(
 *          $pForm('input')
 *              ->name('bar')
 *              ->id('baz')
 *      )
 *
 *      ->append(
 *          $pForm('input')
 *              ->type('submit')
 *              ->value('Done!')
 *      );
 *
 * echo $pForm;
 *
 *
 * Will create this output (without formatting):
 *
 *
 * <form method="POST" action="?foo">
 *     Enter your <b>name</b>:
 *     <input name="bar" id="baz" />
 *     <input type="submit" value="Done!" />
 * </form>
 *
 *
 * @category  NoSaladNet
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2014 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/NoSaladNet/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/NoSaladNet
 * @since     $VERSION$
 */
class pQuery
{
    protected $_tag;

    protected $_children = array();

    protected $_settings = array();


    public function __construct($tag = 'div')
    {
        $this->_tag = $tag;
    }


    function __get($name)
    {
        $static = new static($name);

        $this->append($static);

        return $static;
    }


    public function __toString()
    {
        $out = '<' . $this->_tag;

        foreach (array_filter($this->_settings) as $key => $value)
        {
            $out .= ' ' . $key . '="' . $value . '"';
        }

        if (count($this->_children) == 0)
        {
            return $out . '/>';
        }

        $out .= '>';

        foreach ($this->_children as $childNode)
        {
            $out .= $childNode;
        }

        $tag = strstr($this->_tag, ' ', true);
        if (!$tag)
        {
            $tag = $this->_tag;
        }

        $out .= '</' . $tag . '>';

        return $out;
    }


    public function __set($name, $value)
    {
        $static = $this->__get($name);
        $static->append($value);

        return $static;
    }


    public function append($value)
    {
        if (is_array($value))
        {
            $this->_children = array_merge($this->_children, $value);

            return $this;
        }

        $this->_children[] = $value;

        return $this;
    }


    /**
     * .
     *
     * @param $name
     *
     * @return $this
     */
    public function __invoke($name)
    {
        return new pQuery($name);
    }


    function __call($name, $arguments)
    {
        if (count($arguments) > 0)
        {
            $this->_settings[$name] = $arguments[0];

            return $this;
        }

        if (isset($this->_settings[$name]))
        {
            return $this->_settings[$name];
        }

        return $this;
    }
}
