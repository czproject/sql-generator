<?php

	namespace CzProject\SqlGenerator;


	class TableName
	{
		/** @var string[] */
		private $parts;


		/**
		 * @param string ...$parts
		 */
		public function __construct(...$parts)
		{
			$this->parts = $parts;
		}


		/**
		 * @return string
		 */
		public function toString(IDriver $driver)
		{
			$res = [];

			foreach ($this->parts as $part) {
				$res[] = $driver->escapeIdentifier($part);
			}

			return implode('.', $res);
		}


		/**
		 * @param  string $name
		 * @return self
		 */
		public static function create($name)
		{
			$parts = explode('.', $name);
			return new self(...$parts);
		}
	}
