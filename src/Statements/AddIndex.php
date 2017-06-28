<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\OutOfRangeException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class AddIndex implements IStatement
	{
		/** @var IndexDefinition */
		private $definition;


		/**
		 * @param  string|NULL
		 * @param  string
		 */
		public function __construct($name = NULL, $type)
		{
			$this->definition = new IndexDefinition($name, $type);
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  int|NULL
		 * @return self
		 */
		public function addColumn($column, $order = IndexColumnDefinition::ASC, $length = NULL)
		{
			$this->definition->addColumn($column, $order, $length);
			return $this;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			return 'ADD ' . $this->definition->toSql($driver);
		}
	}
