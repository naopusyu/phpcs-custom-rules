# phpcs-custom-rules

[PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)のカスタムルールを作ってみた

## Installation

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
