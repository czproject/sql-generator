<?php

	declare(strict_types=1);

	namespace Tests;

	use CzProject\SqlGenerator\IDriver;


	class DummyDriver implements IDriver
	{
		public function escapeIdentifier($value)
		{
			// @see http://dev.mysql.com/doc/refman/5.0/en/identifiers.html
			// @see http://api.dibiphp.com/2.3.2/source-drivers.DibiMySqlDriver.php.html#307
			return '`' . str_replace('`', '``', $value) . '`';
		}


		public function escapeText($value)
		{
			// https://dev.mysql.com/doc/refman/5.5/en/string-literals.html
			// http://us3.php.net/manual/en/function.mysql-real-escape-string.php#101248
			return '\'' . str_replace(
				['\\', "\0", "\n", "\r", "\t", "'", '"', "\x1a"],
				['\\\\', '\\0', '\\n', '\\r', '\\t', "\\'", '\\"', '\\Z'],
				$value
			) . '\'';
		}


		public function escapeBool($value)
		{
			return $value ? 'TRUE' : 'FALSE';
		}


		public function escapeDate($value)
		{
			if (!($value instanceof \DateTime) && !($value instanceof \DateTimeInterface)) {
				$value = new \DateTime($value);
			}
			return $value->format("'Y-m-d'");
		}


		public function escapeDateTime($value)
		{
			if (!($value instanceof \DateTime) && !($value instanceof \DateTimeInterface)) {
				$value = new \DateTime($value);
			}
			return $value->format("'Y-m-d H:i:s'");
		}
	}
