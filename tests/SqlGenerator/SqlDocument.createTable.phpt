<?php

declare(strict_types=1);

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use CzProject\SqlGenerator\Statements\IndexDefinition;
use CzProject\SqlGenerator\Value;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$contactTable = $sql->createTable('contact')
		->setComment('Clients table.')
		->setOption('AUTO_INCREMENT', Value::create(123))
		->setOption('CHECKSUM', Value::create(FALSE))
		->setOption('COMPRESSION', Value::create('NONE'))
		->setOption('ENGINE', 'InnoDB');
	$contactTable->addColumn('id', 'INT', NULL, ['UNSIGNED' => NULL])
		->setAutoIncrement();
	$contactTable->addColumn('name', 'VARCHAR(100)')
		->setComment('Client name');
	$contactTable->addColumn('surname', 'VARCHAR(100)');
	$contactTable->addColumn('active', 'TINYINT', [1], [
			'UNSIGNED' => NULL,
			'MYOPTION' => Value::create('abc'),
		])
		->setDefaultValue(TRUE);
	$contactTable->addColumn('status', 'ENUM', ['new', 'verified'])
		->setDefaultValue('new');
	$contactTable->addColumn('created', 'DATETIME');
	$contactTable->addColumn('removed', 'DATETIME')
		->setNullable();

	$contactTable->addIndex(NULL, IndexDefinition::TYPE_PRIMARY)
		->addColumn('id');

	$contactTable->addIndex('name_surname', IndexDefinition::TYPE_UNIQUE)
		->addColumn('name', 'ASC', 100)
		->addColumn('surname', 'DESC', 100);

	$contactTable->addForeignKey('fk_creator', 'creator_id', 'user', 'id');

	Assert::same(implode("\n", [
		'CREATE TABLE `contact` (',
		"\t`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,",
		"\t`name` VARCHAR(100) NOT NULL COMMENT 'Client name',",
		"\t`surname` VARCHAR(100) NOT NULL,",
		"\t`active` TINYINT(1) UNSIGNED MYOPTION 'abc' NOT NULL DEFAULT 1,",
		"\t`status` ENUM('new', 'verified') NOT NULL DEFAULT 'new',",
		"\t`created` DATETIME NOT NULL,",
		"\t`removed` DATETIME NULL,",
		"\tPRIMARY KEY (`id`),",
		"\tUNIQUE KEY `name_surname` (`name` (100), `surname` (100) DESC),",
		"\tCONSTRAINT `fk_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
		')',
		'COMMENT \'Clients table.\'',
		'AUTO_INCREMENT=123',
		'CHECKSUM=0',
		'COMPRESSION=\'NONE\'',
		'ENGINE=InnoDB;',
		'',
	]), $sql->toSql($driver));

});


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$contactTable = $sql->createTable('contact');
	$contactTable->addColumn('name', 'VARCHAR(100)');

	Assert::exception(function () use ($contactTable) {

		$contactTable->addColumn('name', 'VARCHAR(50)');

	}, \CzProject\SqlGenerator\DuplicateException::class, "Column 'name' already exists.");

});


test(function () {

	$sql = new SqlDocument;

	$contactTable = $sql->createTable('contact');
	$contactTable->addIndex('name', 'INDEX');

	Assert::exception(function () use ($contactTable) {

		$contactTable->addIndex('name', 'INDEX');

	}, \CzProject\SqlGenerator\DuplicateException::class, "Index 'name' already exists.");

});


test(function () {

	$sql = new SqlDocument;

	$contactTable = $sql->createTable('contact');
	$contactTable->addForeignKey('fk_person', 'person_id', 'person', 'id');

	Assert::exception(function () use ($contactTable) {

		$contactTable->addForeignKey('fk_person', 'person_id', 'person', 'id');

	}, \CzProject\SqlGenerator\DuplicateException::class, "Foreign key 'fk_person' already exists.");

});
