<?php
/**
 * TYPO3_Sniffs_Debug_DebugCodeSniff.
 *
 * PHP version 5
 *
 * @category  Debug
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
/**
 * Warns about the use of debug code like
 * print_r(), var_dump(), xdebug and debug.
 *
 * @category  Debug
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2010 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 */
class BetterCollectiveCodeStandard_Sniffs_Debug_DebugCodeSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_STRING, T_COMMENT,);
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $tokenType = $tokens[$stackPtr]['type'];
        $currentToken = $tokens[$stackPtr]['content'];
        switch ($tokenType) {
        case 'T_STRING':
            if ($currentToken === 'debug') {
                $previousToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
                if ($tokens[$previousToken]['content'] === '::') {
                    $errorData = array($tokens[$previousToken - 1]['content']);
                    $error = 'Call to debug function %s::debug() must be removed';
                    $phpcsFile->addError($error, $stackPtr, 'StaticDebugCall', $errorData);
                } elseif (($tokens[$previousToken]['content'] === '->') || ($tokens[$previousToken]['content'] === 'class') || ($tokens[$previousToken]['content'] === 'function')) {
                    // We don't care about code like:
                    // if ($this->debug) {...}
                    // class debug {...}
                    // function debug () {...}
                    return;
                } else {
                    $errorData = array($currentToken);
                    $error = 'Call to debug function %s() must be removed';
                    $phpcsFile->addError($error, $stackPtr, 'DebugFunctionCall', $errorData);
                }
            } elseif ($currentToken === 'print_r' || $currentToken === 'var_dump' || $currentToken === 'xdebug') {
                $errorData = array($currentToken);
                $error = 'Call to debug function %s() must be removed';
                $phpcsFile->addError($error, $stackPtr, 'NativDebugFunction', $errorData);
            }
            break;
        case 'T_COMMENT':
            $comment = $tokens[$stackPtr]['content'];
            $ifDebugInComment = preg_match_all('/\b(([x]?debug)|(print_r)|(var_dump))([\s]+)?\(/', $comment, $matchesArray);
            if ($ifDebugInComment === 1) {
                $error = 'Its not enough to comment out debug functions calls; ';
                $error.= 'they must be removed from code.';
                $phpcsFile->addError($error, $stackPtr, 'CommentOutDebugCall');
            }
            break;
        default:
        }
    }
}
?>