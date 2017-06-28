<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class DropColumn implements IStatement
	{
		/** @var string */
		private $column;


		/**
		 * @param  string
		 */
		public function __construct($column)
		{
			$this->column = $column;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return 'DROP COLUMN ' . $driver->escapeIdentifier($this->column);
		}
	}
