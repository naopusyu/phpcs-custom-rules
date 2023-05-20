<?php

declare(strict_types=1);

namespace Naopusyu\PHPCSCustomRules\Sniffs\NamingConventions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class SnakeCaseVariableNameSniff implements Sniff
{
    public function register()
    {
        return [T_VARIABLE];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $variableName = ltrim($tokens[$stackPtr]['content'], '$');

        if (!preg_match('/^[a-z][a-z0-9_]*$/', $variableName)) {
            $error = 'Variable name "%s" is not in snake_case format';
            $data  = [$variableName];
            $phpcsFile->addError($error, $stackPtr, 'found', $data);
        }
    }
}
