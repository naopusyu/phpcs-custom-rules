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

                $tagLine = $tokens[$stackPtr]['line'];
                $openTag = $phpcsFile->findPrevious(T_DOC_COMMENT_OPEN_TAG, $stackPtr);
                $closeTag = $tokens[$openTag]['comment_closer'];

                $removeDocBlock = true;
                if (count($tokens[$openTag]['comment_tags']) > 1) {
                    $removeDocBlock = false;
                } else {
                    for ($i = $openTag; $i <= $closeTag; $i++) {
                        if ($tokens[$i]['line'] === $tagLine) {
                            continue;
                        }

                        if ($tokens[$i]['code'] === T_DOC_COMMENT_STRING) {
                            $removeDocBlock = false;
                            break;
                        }
                    }
                }

                $phpcsFile->fixer->beginChangeset();

                // prefixed the method name with test
                $newName = 'test' . ucfirst($tokens[$name]['content']);
                $phpcsFile->fixer->replaceToken($name, $newName);

                // remove @test annotation
                $end = $phpcsFile->findNext(T_DOC_COMMENT_WHITESPACE, $stackPtr, null, false, $phpcsFile->eolChar);
                for ($i = $end; $tokens[$i]['line'] === $tagLine; $i--) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }

                // remove doc block
                if ($removeDocBlock === true) {
                    $line = $tokens[$openTag]['line'];
                    for ($i = ($closeTag + 1); $tokens[$i]['line'] >= $line; $i--) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }
                }

                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
