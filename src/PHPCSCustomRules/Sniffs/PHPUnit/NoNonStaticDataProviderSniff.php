<?php

namespace MyStandard\Sniffs\PHPUnit;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class NoNonStaticDataProviderSniff implements Sniff
{
    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        // @dataProviderが存在するか調べる
        if ($tokens[$stackPtr]['content'] !== '@dataProvider') {
            return;
        }

        // アノテーションからデータプロバイダメソッド名を取得
        // @dataProviderの次のトークンは通常、メソッド名
        $providerNamePtr = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $stackPtr + 1);
        if ($providerNamePtr === false) {
            return;
        }
        $providerName = trim($tokens[$providerNamePtr]['content']);

        // クラス全体からデータプロバイダメソッドの定義を探す
        $providerMethodPtr = null;
        for ($i = 0; $i < $phpcsFile->numTokens; $i++) {
            if ($tokens[$i]['code'] === T_FUNCTION) {
                $methodNamePtr = $phpcsFile->findNext(T_STRING, $i);
                if ($methodNamePtr !== false && $tokens[$methodNamePtr]['content'] === $providerName) {
                    $providerMethodPtr = $i;
                    break;
                }
            }
        }

        // 見つからない場合は終了
        if ($providerMethodPtr === null) {
            return;
        }

        // データプロバイダメソッドに'static'修飾子があるかをチェック
        $staticPtr = $phpcsFile->findPrevious([T_STATIC], $providerMethodPtr);
        if ($staticPtr === false || $tokens[$staticPtr]['code'] !== T_STATIC) {
            // 'static'キーワードがない場合、エラーを報告
            $phpcsFile->addError(
                'DataProvider method "%s" must be static.',
                $providerMethodPtr,
                'NotStatic',
                [$providerName]
            );
        }
    }
}
