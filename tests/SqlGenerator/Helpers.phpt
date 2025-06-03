<?php

declare(strict_types=1);

use CzProject\SqlGenerator\Drivers;
use CzProject\SqlGenerator\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::exception(function () {
		new Helpers;
	}, \CzProject\SqlGenerator\StaticClassException::class, 'This is static class.');
});


test(function () {
	$driver = new Drivers\MysqlDriver;

	Assert::same('\'Lorem Ipsum\'', Helpers::formatValue('Lorem Ipsum', $driver));
	Assert::same('123', Helpers::formatValue(123, $driver));
	Assert::same('123.4567', Helpers::formatValue(123.4567, $driver));
	Assert::same('1', Helpers::formatValue(TRUE, $driver));
	Assert::same('0', Helpers::formatValue(FALSE, $driver));
	Assert::same('NULL', Helpers::formatValue(NULL, $driver));
	Assert::same('\'2017-01-01 21:12:00\'', Helpers::formatValue(new \DateTime('2017-01-01 21:12:00', new \DateTimeZone('UTC')), $driver));

	Assert::exception(function () use ($driver) {
		Helpers::formatValue([], $driver);
	}, \CzProject\SqlGenerator\InvalidArgumentException::class, 'Unsupported value type.');
});
