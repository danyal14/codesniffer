<?php
/**
 * BetterCollectiveCodeStandard_Sniffs_Files_FilenameSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @copyright 2013 Stefano Kowalke
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class BetterCollectiveCodeStandard_Sniffs_Files_FilenameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);
    } //end register()

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
        $findTokens = array(
                       T_CLASS,
                       T_INTERFACE
                      );

        $stackPtr = $phpcsFile->findNext($findTokens, ($stackPtr + 1));

        // Check if we hit a file without a class, return
        if (!$stackPtr) {
            return;
        }
        $classNameToken = $phpcsFile->findNext(T_STRING, $stackPtr);
        $className      = $tokens[$classNameToken]['content'];
        $fullPath = basename($phpcsFile->getFileName());
        $fileName = substr($fullPath, 0, strrpos($fullPath, '.'));
        if ($fileName === '') {
            // No filename probably means STDIN, so we can't do this check.
            return;
        }

        if (strcmp(strtolower($fileName), strtolower($className))) {
            $error = 'The classname is not equal to the filename; found "%s" as classname and "%s" for filename.';
            $data = array(
                        $className,
                        $fileName . '.php'
                    );
            $phpcsFile->addError($error, $stackPtr, 'ClassnameNotEqualToFilename', $data);
        }

        if (strtolower($fileName) !== $fileName) {
            $error = 'The filename has to be in lower casing; found "%s".';
            $data = array(
                        $fileName . '.php'
                    );
            $phpcsFile->addError($error, $stackPtr, 'NotLowercaseFilename', $data);
        }

        if ($tokens[$stackPtr]['code'] === T_INTERFACE) {
            if (!stristr($fileName, 'Interface')) {
                $error = 'The file contains an interface but the string "Interface" is not part of the filename; found "%s", but expected "%s".';
                $data = array(
                            $fileName . '.php',
                            $className . '.php'
                        );
                $phpcsFile->addError($error, $stackPtr, 'InterfaceNotInFilename', $data);
            }
        }
    } //end process()
} //end class

?>