<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class DropTable implements IStatement
	{
		/** @var string */
		private $tableName;


		/**
		 * @param  string
		 */
		public function __construct($tableName)
		{
			$this->tableName = $tableName;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return 'DROP TABLE ' . $driver->escapeIdentifier($this->tableName) . ';';
		}
	}
