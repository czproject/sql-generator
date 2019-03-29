<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use CzProject\SqlGenerator\Statements\IndexDefinition;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$sql->command('SET NAMES "utf8mb4"');

	Assert::same(implode("\n", array(
		'SET NAMES "utf8mb4";',
		'',
	)), $sql->toSql($driver));

});
