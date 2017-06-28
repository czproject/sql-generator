<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class Insert implements IStatement
	{
		/** @var string */
		private $tableName;

		/** @var array */
		private $data;


		/**
		 * @param  string
		 * @param  array
		 */
		public function __construct($tableName, array $data)
		{
			$this->tableName = $tableName;
			$this->data = $data;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			$output = 'INSERT INTO ' . $driver->escapeIdentifier($this->tableName);

			// columns
			$output .= ' (';
			$output .= implode(', ', array_map(array($driver, 'escapeIdentifier'), array_keys($this->data)));
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
