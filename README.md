# phpcs-custom-rules

[PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer/)のカスタムルールを作ってみた

## Installation

PHP 8.4 以上と PHP_CodeSniffer 4 系が必要です。CI では PHP 8.4・8.5・8.6 を検証します。PHP_CodeSniffer 3 系を使う場合は、このパッケージの旧版を利用してください。

```
composer req naopusyu/phpcs-custom-rules --dev
```

## Usage

Create `phpcs.xml` file.

```xml
<?xml version="1.0"?>
<ruleset>
    <arg name="colors" />
    <arg value="ps" />

    <rule ref="vendor/naopusyu/phpcs-custom-rules/src/PHPCSCustomRules/ruleset.xml"/>
</ruleset>
```

Then run the phpcs command.

```
vendor/bin/phpcs src
```

## Development

リポジトリのテストには PHPUnit 13 系を使用します。

```sh
composer install
composer test
```
