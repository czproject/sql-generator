<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class AddIndex implements IStatement
	{
		/** @var IndexDefinition */
		private $definition;


		/**
		 * @param  string|NULL $name
		 * @param  string $type
		 */
		public function __construct($name, $type)
		{
			$this->definition = new IndexDefinition($name, $type);
		}


		/**
		 * @param  string $column
		 * @param  string $order
		 * @param  int|NULL $length
		 * @return static
		 */
		public function addColumn($column, $order = IndexColumnDefinition::ASC, $length = NULL)
		{
			$this->definition->addColumn($column, $order, $length);
			return $this;
		}


		public function toSql(IDriver $driver)
		{
			return 'ADD ' . $this->definition->toSql($driver);
		}
	}
