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

namespace NoSaladNet;

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

    protected $_attributeSet = array();

    protected $_parent;


    /**
     * @param string $tag The container where everything will reside.
     */
    public function __construct($tag = 'div', $parent = null)
    {
        $this->_parent = $parent;
        $this->_tag = $tag;
    }


    /**
     * Get the parent.
     *
     * @return static|null
     */
    public function getParent()
    {
        return $this->_parent;
    }


    /**
     * Just returning some HTML.
     *
     * @return string
     */
    public function __toString()
    {
        $out = '<' . $this->_tag;

        foreach (array_filter($this->_attributeSet) as $key => $value)
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


    /**
     * Add a new node inside.
     *
     *
     * The code:
     *
     * $p = new pQuery('p');
     * $p->append('some text');
     *
     * Will produce:
     *
     * <p>some text</p>
     *
     * @param $value The content which can be another pQuery-Node too.
     *
     * @return $this
     */
    public function append($value)
    {
        if (is_array($value))
        {
            $this->_children = array_merge($this->_children, $value);

            return $this;
        }

        if ($value instanceof \Closure)
        {
            $value = $value();
        }

        $this->_children[] = $value;

        return $this;
    }


    /**
     * Create a new HTML element.
     *
     *
     * Like:
     *
     * $p = new pQuery('p');
     *
     * $span = $p('span'); // same as "$span = new pQuery('span');"
     *
     * @param $name HTML-Node
     *
     * @return static
     */
    public function __invoke($name)
    {
        return new pQuery($name, $this);
    }


    /**
     * Get or change an attribute.
     *
     * $warning = new pQuery('span');
     *
     * // set style="warn"
     * $warning->style('warn');
     *
     * // get whats inside
     * $warning->style();
     *
     *
     * @param $name Name of the attribute to change
     * @param $arguments
     *
     * @return $this
     */
    function __call($name, $arguments)
    {
        if (count($arguments) > 0)
        {
            return $this->attr($name, $arguments[0]);
        }

        return $this->attr($name);
    }


    /**
     * Get or set an attribute or all attributes.
     *
     * Imagine an input:
     *
     *      $input = new pQuery('input');
     *      $input->name('bar')->value('priceless');
     *
     * You can play around with the attributes easily:
     *
     *      $allAttributes   = $input->attr();        // get all of 'em
     *
     *      $singleAttribute = $input->attr('value'); // look up what the value is
     *      $singleAttribute = $input->value();       // would be the same
     *
     *      $input->('name', 'baz');                  // change an attribute
     *      $input->name('baz');                      // would be the same ;)
     *
     *
     * @param mixed $attributeName
     * @param mixed $attributeValue
     *
     * @return $this
     */
    public function attr($attributeName = null, $attributeValue = false)
    {
        if (null == $attributeName)
        {
            return $this->_attributeSet;
        }

        if (false == $attributeValue)
        {
            if (isset($this->_attributeSet[$attributeName]))
            {
                return $this->_attributeSet[$attributeName];
            }

            return null;
        }

        $this->_attributeSet[$attributeName] = $attributeValue;

        return $this;
    }
}
