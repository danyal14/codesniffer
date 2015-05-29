<?php
/**
 * Exactly one pair of opening and closing tags are allowed
 *
 * @category  PHP
 */
class BetterCollectiveCodeStandard_Sniffs_PHP_UpperCaseConstantSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_TRUE,
            T_FALSE,
            T_NULL,
        );
    }//end register()

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $keyword = $tokens[$stackPtr]['content'];
        $expected = strtoupper($keyword);
        if ($keyword !== $expected) {
            if ($keyword === strtolower($keyword)) {
                $phpcsFile->recordMetric($stackPtr, 'PHP constant case', 'lower');
            } else {
                $phpcsFile->recordMetric($stackPtr, 'PHP constant case', 'mixed');
            }
            $error = 'TRUE, FALSE and NULL must be uppercase; expected "%s" but found "%s"';
            $data  = array(
                $expected,
                $keyword,
            );
            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'Found', $data);
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($stackPtr, $expected);
            }
        } else {
            $phpcsFile->recordMetric($stackPtr, 'PHP constant case', 'upper');
        }
    }//end process()

}//end class
?>
