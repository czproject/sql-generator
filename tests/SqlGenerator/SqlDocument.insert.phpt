<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$sql->insert('contact', array(
		'name' => 'Harry',
		'surname' => 'Potter',
		'active' => FALSE,
		'created' => new DateTime('2016-12-31 16:12:31', new DateTimeZone('UTC')),
		'removed' => NULL,
	));

	Assert::same(implode('', array(
		"\n",
		'INSERT INTO `contact` (',
		implode(', ', array(
			'`name`',
			'`surname`',
			'`active`',
			'`created`',
			'`removed`',
		)),
		")\nVALUES (",
		implode(', ', array(
			"'Harry'",
			"'Potter'",
			'0',
			"'2016-12-31 16:12:31'",
			'NULL',
		)),
		");\n",
	)), $sql->toSql($driver));

});
