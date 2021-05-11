<?php

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\SqlDocument;
use CzProject\SqlGenerator\Statements\IndexDefinition;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

define('TEMP_DIR', prepareTempDir());


test(function () {

	$sql = new SqlDocument;
	$driver = new Drivers\MysqlDriver;
	$file = TEMP_DIR . '/subdir/file.sql';

	$sql->command('SET NAMES "utf8mb4"');
	$sql->save($file, $driver);

	Assert::same(implode("\n", [
		'SET NAMES "utf8mb4";',
		'',
	]), file_get_contents($file));

});


test(function () {

	$sql = new SqlDocument;
	$sql->command('SET NAMES "utf8mb4"');
	$file = TEMP_DIR . '/subdir';

	Assert::exception(function () use ($sql, $file) {
		$sql->save($file, new Drivers\MysqlDriver);
	}, \CzProject\SqlGenerator\IOException::class, "Unable to write file '$file'.");

});
