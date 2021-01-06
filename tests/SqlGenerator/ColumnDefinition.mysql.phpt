<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\Statements\ColumnDefinition;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../libs/DummyDriver.php';


test(function () {

	$column = new ColumnDefinition('name', 'VARCHAR', [100], [
		'OPTION' => 'option_value',
		'COLLATE' => 'latin2_general_ci',
		'CHARACTER SET' => 'latin2',
	]);
	Assert::same('`name` VARCHAR(100) CHARACTER SET latin2 COLLATE latin2_general_ci OPTION option_value NOT NULL', $column->toSql(new Drivers\MysqlDriver));

});
