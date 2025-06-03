<?php

declare(strict_types=1);

use CzProject\SqlGenerator\SqlDocument;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {

	$sql = new SqlDocument;
	Assert::true($sql->isEmpty());

	$sql->createTable('book');
	Assert::false($sql->isEmpty());
});
