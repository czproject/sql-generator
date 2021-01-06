<?php

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


function test($cb)
{
	$cb();
}


function prepareTempDir()
{
	$tempDir = __DIR__ . '/tmp/' . getmypid();
	@mkdir(dirname($tempDir), 0777, TRUE);
	Tester\Helpers::purge($tempDir);
	return $tempDir;
}
