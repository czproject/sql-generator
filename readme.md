# CzProject\SqlGenerator

[![Build Status](https://github.com/czproject/sql-generator/workflows/Build/badge.svg)](https://github.com/czproject/sql-generator/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/czproject/sql-generator.svg)](https://packagist.org/packages/czproject/sql-generator)
[![Latest Stable Version](https://poser.pugx.org/czproject/sql-generator/v/stable)](https://github.com/czproject/sql-generator/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/czproject/sql-generator/blob/master/license.md)

<a href="https://www.janpecha.cz/donate/"><img src="https://buymecoffee.intm.org/img/donate-banner.v1.svg" alt="Donate" height="100"></a>


## Installation

[Download a latest package](https://github.com/czproject/sql-generator/releases) or use [Composer](http://getcomposer.org/):

```
composer require czproject/sql-generator
```

`CzProject\SqlGenerator` requires PHP 5.6 or later.


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

## Special Values

There are value objects for specific cases:

### TableName

Delimited table name.

```php
use CzProject\SqlGenerator\TableName;

$table = $sql->createTable(TableName::create('schema.table'))
$table->addForeignKey('fk_table_id', 'id', TableName::create('schema2.table2'), 'id');
// and more ($sql->renameTable(),...)
```


### Value

Scalar/stringable/datetime value. It can be used in option values.

```php
use CzProject\SqlGenerator\Value;

$table->setOption('AUTO_INCREMENT', Value::create(123)); // generates AUTO_INCREMENT=123
$table->setOption('CHECKSUM', Value::create(FALSE)); // generates CHECKSUM=0
$table->setOption('COMPRESSION', Value::create('NONE')); // generates COMPRESSION='NONE'
```


## Supported database

Currently is supported common SQL and MySQL.


------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/
