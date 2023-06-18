<?php

declare(strict_types=1);

namespace Naopusyu\PHPCSCustomRules\Sniffs\DocComment;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ShortFormTypeKeywordsSniff implements Sniff
{
    public function register()
    {
        return [T_DOC_COMMENT_OPEN_TAG];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $closeTag = $tokens[$stackPtr]['comment_closer'];

        for ($i = $stackPtr; $i < $closeTag; $i++) {
            if ($tokens[$i]['code'] === T_DOC_COMMENT_STRING) {

                $content = $tokens[$i]['content'];

                [$type] = explode(' ', $content);
                if ($type !== 'boolean' && $type !== 'integer') {
                    continue;
                }

                $error = 'Short form type keywords must be used. Found: %s';
                $data = [$type];
                
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }
    }
}
