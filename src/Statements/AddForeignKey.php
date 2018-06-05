<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\OutOfRangeException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class AddForeignKey implements IStatement
	{
		/** @var ForeignKeyDefinition */
		private $definition;


		/**
		 * @param  string
		 * @param  string[]|string
		 * @param  string
		 * @param  string[]|string
		 */
		public function __construct($name, $columns = array(), $targetTable, $targetColumns = array())
		{
			$this->definition = new ForeignKeyDefinition($name, $columns, $targetTable, $targetColumns);
		}


		/**
		 * @param  int
		 * @return static
		 */
		public function setOnUpdateAction($onUpdateAction)
		{
			$this->definition->setOnUpdateAction($onUpdateAction);
			return $this;
		}


		/**
		 * @param  int
		 * @return static
		 */
		public function setOnDeleteAction($onDeleteAction)
		{
			$this->definition->setOnDeleteAction($onDeleteAction);
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
