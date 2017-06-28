<?php

	namespace CzProject\SqlGenerator;


	class Helpers
	{
		public function __construct()
		{
			throw new StaticClassException('This is static class.');
		}


		/**
		 * @param  mixed
		 * @return string
		 * @throws Exception
		 * @see    https://api.dibiphp.com/3.0/source-Dibi.Translator.php.html#174
		 */
		public static function formatValue($value, IDriver $driver)
		{
			if (is_string($value)) {
				return $driver->escapeText($value);

			} elseif (is_int($value)) {
				return (string) $value;

			} elseif (is_float($value)) {
				return rtrim(rtrim(number_format($value, 10, '.', ''), '0'), '.');

			} elseif (is_bool($value)) {
				return $driver->escapeBool($value);

			} elseif ($value === NULL) {
				return 'NULL';

			} elseif ($value instanceof \DateTime || $value instanceof \DateTimeInterface) {
				return $driver->escapeDateTime($value);
			}

			throw new InvalidArgumentException("Unsupported value type.");
		}
	}
