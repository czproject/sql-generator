<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


/**
 * @return void
 */
function test(callable $cb)
{
	$cb();
}


/**
 * @return string
 */
function prepareTempDir()
{
	$tempDir = __DIR__ . '/tmp/' . getmypid();
	@mkdir(dirname($tempDir), 0777, TRUE);
	Tester\Helpers::purge($tempDir);
	return $tempDir;
}
