<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class SqlCommand implements IStatement
	{
		/** @var string */
		private $command;


		/**
		 * @param  string
		 */
		public function __construct($command)
		{
			$this->command = $command;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return rtrim($this->command, ';') . ';';
		}
	}
