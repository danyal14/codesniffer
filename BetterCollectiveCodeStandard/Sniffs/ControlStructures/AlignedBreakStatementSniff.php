<?php
/**
 * BetterCollectiveCodeStandard_Sniffs_ControlStructures_AlignedBreakStatementSniff.
 *
 * TYPO3 version 4
 *
 * @category  ControlStructures
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
/**
 * Checks that the include_once is used in all cases.
 *
 * @category  ControlStructures
 * @author    Laura Thewalt <laura.thewalt@wmdb.de>
 * @copyright 2010 Laura Thewalt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 */
class BetterCollectiveCodeStandard_Sniffs_ControlStructures_AlignedBreakStatementSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_BREAK);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_condition'])) {
            $checkTokenIndex = $tokens[$stackPtr]['scope_condition'];
        } else {
            $scopeToken = array_keys($tokens[$stackPtr]['conditions']);
            $checkTokenIndex = $scopeToken[count($scopeToken) - 1];
        }

        if ($tokens[$checkTokenIndex]['type'] == 'T_ELSE') {
            if ($tokens[$stackPtr]['column'] != ($tokens[$checkTokenIndex]['column'] - 1)) {
                $phpcsFile->addError('Break Statement must have the same indent than the scope.', $stackPtr);
            }
        }
    }
}
?>