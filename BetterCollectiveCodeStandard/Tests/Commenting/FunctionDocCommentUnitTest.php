<?php
/**
 * Unit test class for FunctionDocCommentSniff.
 *
 * PHP version 5
 *
 * @category  Commenting
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
/**
 * Unit test class for FunctionDocCommentSniff.
 *
 * This unit test was copied and modified
 * from PEAR.Commenting.FunctionCommentSniff.
 * Thanks for this guys!
 *
 * @category  Commenting
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 */
class BetterCollectiveCodeStandard_Tests_Commenting_FunctionDocCommentUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList()
    {
        return array(
                9   => 1,
                10  => 1,
                12  => 1,
                13  => 1,
                24  => 1,
                30  => 1,
                33  => 1,
                47  => 1,
                68  => 1,
                78  => 1,
                92  => 1,
                97  => 1,
                100 => 1,
                109 => 1,
                110 => 1,
                111 => 1,
                112 => 1,
                113 => 2,
                114 => 2,
                115 => 4,
                125 => 2,
                126 => 2,
                127 => 3,
                128 => 3,
                129 => 3,
                141 => 2,
                150 => 1,
                157 => 1,
                167 => 1,
                176 => 3,
                186 => 1,
                197 => 1,
                218 => 1,
                250 => 3,
                251 => 1,
                252 => 1,
                253 => 2,
                254 => 2,
                255 => 4,
            );
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList()
    {
        return array();
    }
}
?>