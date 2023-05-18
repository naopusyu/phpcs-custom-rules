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
                if ($fix === false) {
                    return;
                }

                $phpcsFile->fixer->beginChangeset();

                // prefixed the method name with test
                $newName = 'test' . ucfirst($tokens[$name]['content']);
                $phpcsFile->fixer->replaceToken($name, $newName);

                // remove @test annotation
                $phpcsFile->fixer->replaceToken($stackPtr, '');
                $line = $tokens[$stackPtr]['line'];
                $i = $stackPtr;
                while ($tokens[$i]['line'] === $line) {
                    $phpcsFile->fixer->replaceToken($i, '');
                    $i--;
                }
                $phpcsFile->fixer->replaceToken($i, '');

                $openTag = $phpcsFile->findPrevious(T_DOC_COMMENT_OPEN_TAG, $stackPtr);
                $closeTag = $tokens[$openTag]['comment_closer'];

                $removeDocBlock = true;
                if (count($tokens[$openTag]['comment_tags']) > 1) {
                    $removeDocBlock = false;
                } else {
                    for ($i = $openTag; $i <= $closeTag; $i++) {
                        if ($tokens[$i]['code'] === T_DOC_COMMENT_STRING) {
                            $removeDocBlock = false;
                            break;
                        }
                    }
                }

                if ($removeDocBlock === true) {
                    $line = $tokens[$openTag]['line'];
                    for ($i = $closeTag; $tokens[$i]['line'] >= $line; $i--) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }
                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
