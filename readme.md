
# CzProject\SqlGenerator

[![Tests Status](https://github.com/czproject/sql-generator/workflows/Tests/badge.svg)](https://github.com/czproject/sql-generator/actions)


Support Me
----------

Do you like LeanMapper-extension? Are you looking forward to the **new features**?

<a href="https://www.paypal.com/donate?hosted_button_id=BWR5RJCDLY7SG"><img src="https://buymecoffee.intm.org/img/janpecha-paypal-donate@2x.png" alt="PayPal or credit/debit card" width="254" height="248"></a>

<img src="https://buymecoffee.intm.org/img/bitcoin@2x.png" alt="Bitcoin" height="32"> `bc1qrq9egf99a6z3576twggrp6uv5td5r3pq0j4awe`

Thank you!


## Installation

[Download a latest package](https://github.com/czproject/sql-generator/releases) or use [Composer](http://getcomposer.org/):

```
composer require czproject/sql-generator
```

`CzProject\SqlGenerator` requires PHP 5.6.0 or later.


## Usage

``` php
use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;

$sql = new SqlDocument;
$driver = new Drivers\MysqlDriver;

$contactTable = $sql->createTable('contact')
	->setComment('Clients table.')
	->setOption('ENGINE', 'InnoDB');
$contactTable->addColumn('id', 'INT', NULL, array('UNSIGNED' => NULL))
	->setAutoIncrement();
$contactTable->addColumn('name', 'VARCHAR(100)')
	->setComment('Client name');
$contactTable->addColumn('surname', 'VARCHAR(100)');
$contactTable->addColumn('active', 'unsigned TINYINT')
	->setDefaultValue(TRUE);
$contactTable->addColumn('created', 'DATETIME');
$contactTable->addColumn('removed', 'DATETIME')
	->setNullable();

$contactTable->addIndex(NULL, IndexDefinition::TYPE_PRIMARY)
	->addColumn('id');

$contactTable->addIndex('name_surname', IndexDefinition::TYPE_UNIQUE)
	->addColumn('name', 'ASC', 100)
	->addColumn('surname', 'DESC', 100);

$output = $sql->toSql($driver);
```

Outputs:

``` sql
CREATE TABLE `contact` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL COMMENT 'Client name',
	`surname` VARCHAR(100) NOT NULL,
	`active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
	`created` DATETIME NOT NULL,
	`removed` DATETIME NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name_surname` (`name` (100), `surname` (100) DESC)
)
COMMENT 'Clients table.'
ENGINE=InnoDB;
```


## Statements

There is few predefined statements:

```php
$sql->createTable($tableName);
$sql->dropTable($tableName);
$sql->renameTable($old, $new);
$sql->alterTable($tableName);
$sql->insert($tableName, $data);
$sql->command($command); // for example $sql->command('SET NAMES "utf8"');
$sql->comment($comment);
```

You can add custom statements:

```php
$sql->addStatement(new Statements\CreateTable($tableName));
```

Check if is SQL document empty:

```php
$sql->isEmpty();
```

Generate SQL:

```php
$sql->toSql($driver); // returns string
$sql->getSqlQueries($driver); // returns string[]
$sql->save($file, $driver); // saves SQL into file
```

## Supported database

Currently is supported common SQL and MySQL.


------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/
