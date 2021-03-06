<?php
/**
 * Ensures all class keywords are lowercase.
 *
 * PHP version 5
 *
 * @category  Classes
 * @version   Release: @package_version@
 */
/**
 * Ensures all class keywords are lowercase.
 *
 * @category  Classes
 * @package   TYPO3_PHPCS_Pool
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 * @link      https://github.com/typo3-ci/TYPO3SniffPool
 */
class BetterCollectiveCodeStandard_Sniffs_Classes_LowercaseClassKeywordsSniff extends Squiz_Sniffs_Classes_LowercaseClassKeywordsSniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CLASS,
                T_INTERFACE,
                T_EXTENDS,
                T_IMPLEMENTS,
                T_ABSTRACT,
                T_FINAL,
                T_TRAIT,
                T_VAR,
                T_CONST,
                T_PRIVATE,
                T_PUBLIC,
                T_PROTECTED
                );
    }
}
?>