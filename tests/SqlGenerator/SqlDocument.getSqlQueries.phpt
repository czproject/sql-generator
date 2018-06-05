<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use CzProject\SqlGenerator\Statements\IndexDefinition;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$contactTable = $sql->createTable('contact')
		->setComment('Clients table.');
	$contactTable->addColumn('id', 'INT', NULL, array('UNSIGNED' => NULL))
		->setAutoIncrement();

	$sql->alterTable('book')
		->dropColumn('name');

	$expected = array();
	$expected[] = implode("\n", array(
		'CREATE TABLE `contact` (',
		"\t`id` INT UNSIGNED NOT NULL AUTO_INCREMENT",
		')',
		'COMMENT \'Clients table.\';',
	));

	$expected[] = implode("\n", array(
		'ALTER TABLE `book`',
		'DROP COLUMN `name`;',
	));

	Assert::same($expected, $sql->getSqlQueries($driver));

});
