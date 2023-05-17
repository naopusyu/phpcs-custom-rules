<?php

declare(strict_types=1);

namespace Naopusyu\PHPCSCustomRules\Sniffs\PHPUnit;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DeprecatedTestAnnotationSniff implements Sniff
{
    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $tag = $tokens[$stackPtr]['content'];

        if ($tag === '@test') {
            $function = $phpcsFile->findNext(T_FUNCTION, $stackPtr);

            $name = $phpcsFile->findNext(T_STRING, $function);
            if (substr($tokens[$name]['content'], 0, 4) !== 'test') {

                $error = 'The "@test" annotation is deprecated. Method names must be prefixed with "test".';
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'Found');
                
                if ($fix === true) {
                    // prefixed the method name with test
                    $newName = 'test' . ucfirst($tokens[$name]['content']);
                    $phpcsFile->fixer->replaceToken($name, $newName);

                    // remove @test annotation
                    $phpcsFile->fixer->replaceToken($stackPtr, '');
                }
            }
        }
    }
}
