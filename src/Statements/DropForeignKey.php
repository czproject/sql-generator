<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class DropForeignKey implements IStatement
	{
		/** @var string */
		private $foreignKey;


		/**
		 * @param  string
		 */
		public function __construct($foreignKey)
		{
			$this->foreignKey = $foreignKey;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return 'DROP FOREIGN KEY ' . $driver->escapeIdentifier($this->foreignKey);
		}
	}
