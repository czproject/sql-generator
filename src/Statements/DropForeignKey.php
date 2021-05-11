<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class DropForeignKey implements IStatement
	{
		/** @var string */
		private $foreignKey;


		/**
		 * @param  string $foreignKey
		 */
		public function __construct($foreignKey)
		{
			$this->foreignKey = $foreignKey;
		}


		public function toSql(IDriver $driver)
		{
			return 'DROP FOREIGN KEY ' . $driver->escapeIdentifier($this->foreignKey);
		}
	}
