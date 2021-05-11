<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use CzProject\SqlGenerator\Statements\IndexDefinition;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$contactTable = $sql->alterTable('contact');
	$contactTable->setOption('ENGINE', 'InnoDB');

	// columns
	$contactTable->addColumn('active', 'TINYINT', [1], ['UNSIGNED' => NULL])
		->setDefaultValue(TRUE)
		->setNullable()
		->setComment('Contact status')
		->moveAfterColumn('name');

	$contactTable->addColumn('id', 'INT')
		->setAutoIncrement()
		->moveToFirstPosition();

	$contactTable->modifyColumn('name', 'VARCHAR(200)')
		->setDefaultValue('XYZ')
		->setComment('Name of contact')
		->setNullable()
		->moveAfterColumn('id');

	$contactTable->modifyColumn('id', 'INT')
		->setAutoIncrement()
		->moveToFirstPosition();

	$contactTable->dropColumn('removed');

	// indexes
	$contactTable->addIndex(NULL, IndexDefinition::TYPE_PRIMARY)
		->addColumn('id');

	$contactTable->dropIndex('name');

	// foreign keys
	$contactTable->dropForeignKey('fk_creator');
	$contactTable->addForeignKey('fk_creator', 'creator_id', 'user', 'id')
		->setOnUpdateAction('NO ACTION')
		->setOnDeleteAction('NO ACTION');

	// comment
	$contactTable->setComment('Table of contacts.');

	Assert::same(implode("\n", [
		'ALTER TABLE `contact`',
		"ADD COLUMN `active` TINYINT(1) UNSIGNED NULL DEFAULT 1 COMMENT 'Contact status' AFTER `name`,",
		"ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT FIRST,",
		"MODIFY COLUMN `name` VARCHAR(200) NULL DEFAULT 'XYZ' COMMENT 'Name of contact' AFTER `id`,",
		"MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT FIRST,",
		"DROP COLUMN `removed`,",
		"ADD PRIMARY KEY (`id`),",
		"DROP INDEX `name`,",
		"DROP FOREIGN KEY `fk_creator`,",
		"ADD CONSTRAINT `fk_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,",
		'COMMENT \'Table of contacts.\',',
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
