
# CzProject\SqlGenerator


Installation
------------

[Download a latest package](https://github.com/czproject/sql-generator/releases) or use [Composer](http://getcomposer.org/):

```
composer require [--dev] czproject/sql-generator
```

`CzProject\SqlGenerator` requires PHP 5.4.0 or later.


## Usage

``` php
<?php

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

------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/
