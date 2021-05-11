<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../libs/DummyDriver.php';


test(function () {

	$sql = new SqlDocument;

	$sql->renameTable('contact', 'client');

	Assert::same("RENAME TABLE `contact` TO `client`;\n", $sql->toSql(new Drivers\MysqlDriver));

	Assert::exception(function () use ($sql) {

		$sql->toSql(new Tests\DummyDriver);

	}, \CzProject\SqlGenerator\NotImplementedException::class, 'Table rename is not implemented for driver Tests\DummyDriver.');

});
