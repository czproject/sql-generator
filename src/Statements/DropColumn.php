<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class DropColumn implements IStatement
	{
		/** @var string */
		private $column;


		/**
		 * @param  string $column
		 */
		public function __construct($column)
		{
			$this->column = $column;
		}


		public function toSql(IDriver $driver)
		{
			return 'DROP COLUMN ' . $driver->escapeIdentifier($this->column);
		}
	}
