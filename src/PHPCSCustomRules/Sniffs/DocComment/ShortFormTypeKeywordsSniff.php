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

                $types = explode('|', $type);

                foreach ($types as $t) {
                    $lower = strtolower($t);
                    switch ($lower) {
                        case 'bool':
                            if ($type !== 'bool') {
                                $error = 'Short form type keywords must be used. Found: %s';
                                $data = [$t];                            
                                $phpcsFile->addError($error, $i, 'Found', $data);            
                            }
                            break;            
                        case 'boolean':
                            $error = 'Short form type keywords must be used. Found: %s';
                            $data = [$t];                            
                            $phpcsFile->addError($error, $i, 'Found', $data);            
                            break;
                        case 'int':
                            if ($type !== 'int') {
                                $error = 'Short form type keywords must be used. Found: %s';
                                $data = [$t];                            
                                $phpcsFile->addError($error, $i, 'Found', $data);            
                            }
                            break;            
                        case 'integer':
                            $error = 'Short form type keywords must be used. Found: %s';
                            $data = [$t];                            
                            $phpcsFile->addError($error, $i, 'Found', $data);
                            break;
                    }
                }
            }
        }
    }
}
