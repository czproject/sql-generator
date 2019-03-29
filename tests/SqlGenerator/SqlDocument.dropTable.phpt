<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$sql->dropTable('contact');

	Assert::same("DROP TABLE `contact`;\n", $sql->toSql($driver));

});
