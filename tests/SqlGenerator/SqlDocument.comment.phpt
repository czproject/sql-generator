<?php

declare(strict_types=1);

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use CzProject\SqlGenerator\Statements\IndexDefinition;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;

	$sql->comment('inline comment');
	$sql->comment("\tblock comment #1\nblock comment #2\n\nblock comment #3\n");

	Assert::same(implode("\n", [
		'-- inline comment',
		'',
		'-- block comment #1',
		'-- block comment #2',
		'-- ',
		'-- block comment #3',
		'',
	]), $sql->toSql($driver));

});
