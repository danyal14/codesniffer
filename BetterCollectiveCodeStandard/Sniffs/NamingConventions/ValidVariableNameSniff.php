<?php
/**
 * BetterCollectiveCodeStandard_Sniffs_NamingConventions_ValidVariableNameSniff.
 *
 * PHP version 5
 *
 * @category  NamingConventions
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Stefano Kowalke, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
if (class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff', true) === false) {
    $error = 'Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
/**
 * Checks the naming of member variables.
 * All identifiers must use camelCase and start with a lower case letter.
 * Underscore characters are not allowed.
 *
 * @category  NamingConventions
 * @author    Stefano Kowalke <blueduck@gmx.net>
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010 Stefano Kowalke, Andy Grunwald
 * @license   http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @version   Release: @package_version@
 */
class BetterCollectiveCodeStandard_Sniffs_NamingConventions_ValidVariableNameSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{
    /**
     * Contains built-in TYPO3 variables which we don't check
     *
     * @var array $allowedBuiltInVariableNames
     * @TODO: Add Phalconphp specific variable names here
     */
    protected $allowedBuiltInVariableNames = array();

    /**
     * Processes class member variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $memberProps = $phpcsFile->getMemberProperties($stackPtr);
        if (empty($memberProps) === true) {
            return;
        }
        $this->processVariableNameCheck($phpcsFile, $stackPtr, 'member ');
    }
    /**
     * Processes normal variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $this->processVariableNameCheck($phpcsFile, $stackPtr);
    }

    /**
     * Processes variables in double quoted strings.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     *
     * @return void
     */
    protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        // We don't care about variables in strings.
        return;
    }

    /**
     * Proceed the whole variable name check.
     * Checks if the variable name has underscores or is written in lowerCamelCase.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position where the token was found.
     * @param string               $scope     The variable scope. For example "member" if variable is a class property.
     *
     * @return void
     */
    protected function processVariableNameCheck(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $scope = '')
    {
        $tokens = $phpcsFile->getTokens();
        $variableName = ltrim($tokens[$stackPtr]['content'], '$');

        if (in_array($variableName, $this->allowedBuiltInVariableNames)) {
            return;
        }

        $hasUnderscores = stripos($variableName, '_');

        // Check if the variable is named "$_" and is the value variable in a foreach statement
        // foreach ($variable as $key => $_) { ...
        // Because if only a key is needed in a foreach loop, the cgl says that the developer
        // has to rename the foreach value variable $_
        if ($variableName === '_' && $this->isVariableValuePartInForEach($phpcsFile, $stackPtr)) {
            return;
        }

        $isLowerCamelCase = PHP_CodeSniffer::isCamelCaps($variableName, false, true, true);
        if ($hasUnderscores !== false) {
            $messageData = array($scope, $variableName);
            $error = 'Underscores are not allowed in the %svariablename "$%s".';

            switch($variableName) {
            default:
                $messageData[] = $this->buildExampleVariableName($variableName);
                $error.= 'Use lowerCamelCase for identifier instead e.g. "$%s"';
            }

            $phpcsFile->addError($error, $stackPtr, 'VariableNameHasUnderscoresNotLowerCamelCased', $messageData);

        } elseif ($isLowerCamelCase === false) {
            $pattern = '/([A-Z]{1,}(?=[A-Z]?|[0-9]))/';
            $variableNameLowerCamelCased = preg_replace_callback(
                $pattern, function ($m) {
                    return ucfirst(strtolower($m[1]));
                }, $variableName
            );

            $messageData = array(ucfirst($scope), lcfirst($variableNameLowerCamelCased), $variableName);
            $error = '%svariablename must be lowerCamelCase; expect "$%s" but found "$%s"';
            $phpcsFile->addError($error, $stackPtr, 'VariableIsNotLowerCamelCased', $messageData);
        }
    }

    /**
     * Checks if a variable name is named $_ and is located in a foreach loop.
     * If this is the case, the variable name $_ is valid.
     *
     * This kind of variable name is valid if this variable is
     * a) used as value part in a foreach loop
     * b) and not used in foreach body
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isVariableValuePartInForEach(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $result = false;
        $tokens = $phpcsFile->getTokens();

        // If we got a variable named $_ and it is not located in a foreach loop
        // There are no parenthesis. Exit here.
        if (isset($tokens[$stackPtr]['nested_parenthesis']) === false) {
            return $result;
        }

        // Look for the foreach token
        $forEachSearch = $phpcsFile->findPrevious(T_FOREACH, $stackPtr, null, false, null, true);
        if ($forEachSearch !== false) {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns a modified variable name.
     * e.g. $my_small_variable => $mySmallVariable
     *
     * @param string $variableName Variable name
     *
     * @return string
     */
    protected function buildExampleVariableName($variableName)
    {
        $newName = '';
        $nameParts = $this->trimExplode('_', $variableName, true);
        $newName = $this->strToLowerStringIfNecessary(array_shift($nameParts));
        foreach ($nameParts as $part) {
            $newName .= ucfirst(strtolower($part));
        }

        return $newName;
    }

    /**
     * If the incomming $namePart is not camel cased, the string will be lowercased.
     *
     * @param string $namePart Part of a variable name (normal string)
     *
     * @return string
     */
    protected function strToLowerStringIfNecessary($namePart)
    {
        if (PHP_CodeSniffer::isCamelCaps($namePart, false, true, true) === false) {
            $namePart = strtolower($namePart);
        }

        return $namePart;
    }

    /**
     * explode()-function with trim() for every element.
     *
     * @param string $delim             The boundary string.
     * @param string $string            The input string
     * @param bool   $removeEmptyValues true if empty values should be removed, false otherwise
     * @param int    $limit             Limit of elements which will be returned
     *
     * @return array
     */
    protected function trimExplode($delim, $string, $removeEmptyValues = false, $limit = 0)
    {
        $explodedValues = explode($delim, $string);

        $result = array_map('trim', $explodedValues);

        if ($removeEmptyValues) {
            $temp = array();
            foreach ($result as $value) {
                if ($value !== '') {
                    $temp[] = $value;
                }
            }
            $result = $temp;
        }

        if ($limit != 0) {
            if ($limit < 0) {
                $result = array_slice($result, 0, $limit);
            } elseif (count($result) > $limit) {
                $lastElements = array_slice($result, $limit - 1);
                $result = array_slice($result, 0, $limit - 1);
                $result[] = implode($delim, $lastElements);
            }
        }

        return $result;
    }
}
?>