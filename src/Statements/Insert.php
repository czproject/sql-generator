<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class Insert implements IStatement
	{
		/** @var string */
		private $tableName;

		/** @var array<string, mixed> */
		private $data;


		/**
		 * @param  string $tableName
		 * @param  array<string, mixed> $data
		 */
		public function __construct($tableName, array $data)
		{
			$this->tableName = $tableName;
			$this->data = $data;
		}


		public function toSql(IDriver $driver)
		{
			$output = 'INSERT INTO ' . $driver->escapeIdentifier($this->tableName);

			// columns
			$output .= ' (';
			$output .= implode(', ', array_map([$driver, 'escapeIdentifier'], array_keys($this->data)));
			$output .= ")\nVALUES (";

			// data
			$fields = count($this->data);

			foreach ($this->data as $value) {
				$output .= Helpers::formatValue($value, $driver);
				$fields--;

				if ($fields > 0) {
					$output .= ', ';
				}
			}

			$output .= ');';
			return $output;
		}
	}
