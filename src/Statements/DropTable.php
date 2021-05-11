<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;
	use CzProject\SqlGenerator\TableName;


	class DropTable implements IStatement
	{
		/** @var string|TableName */
		private $tableName;


		/**
		 * @param  string|TableName $tableName
		 */
		public function __construct($tableName)
		{
			$this->tableName = Helpers::createTableName($tableName);
		}


		public function toSql(IDriver $driver)
		{
			return 'DROP TABLE ' . Helpers::escapeTableName($this->tableName, $driver) . ';';
		}
	}
