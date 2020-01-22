<?php
namespace PHPWee\CssMin;

/**
 * This {@link CssToken CSS token} represents the end of a @variables at-rule block.
 *
 * @package        CssMin/Tokens
 * @link        http://code.google.com/p/cssmin/
 * @author        Joe Scylla <joe.scylla@gmail.com>
 * @copyright    2008 - 2011 Joe Scylla <joe.scylla@gmail.com>
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 * @version        3.0.1
 */
class CssAtVariablesEndToken extends CssAtBlockEndToken
{
    /**
     * Implements {@link CssToken::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "";
    }
}
