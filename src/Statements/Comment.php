<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class Comment implements IStatement
	{
		/** @var string */
		private $comment;


		/**
		 * @param  string
		 */
		public function __construct($comment)
		{
			$this->comment = $comment;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return '-- ' . str_replace("\n", "\n-- ", Helpers::normalizeNewLines(trim($this->comment)));
		}
	}
