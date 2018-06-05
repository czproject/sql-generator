<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\Statements;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../libs/DummyDriver.php';


test(function () {

	$table = new Statements\AlterTable('book');
	$table->dropIndex('contact');
	$table->dropIndex(NULL);

	Assert::same("ALTER TABLE `book`\nDROP INDEX `contact`,\nDROP PRIMARY KEY;", $table->toSql(new Drivers\MysqlDriver));

	Assert::exception(function () use ($table) {

		$table->toSql(new Tests\DummyDriver);

	}, 'CzProject\SqlGenerator\NotImplementedException', 'Drop of primary key is not implemented for driver Tests\DummyDriver.');

});
