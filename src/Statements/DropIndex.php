<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class DropIndex implements IStatement
	{
		/** @var string */
		private $index;


		/**
		 * @param  string
		 */
		public function __construct($index)
		{
			$this->index = $index;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return 'DROP INDEX ' . $driver->escapeIdentifier($this->index);
		}
	}
