<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;
	use CzProject\SqlGenerator\TableName;


	class AddForeignKey implements IStatement
	{
		/** @var ForeignKeyDefinition */
		private $definition;


		/**
		 * @param  string $name
		 * @param  string[]|string $columns
		 * @param  string|TableName $targetTable
		 * @param  string[]|string $targetColumns
		 */
		public function __construct($name, $columns, $targetTable, $targetColumns)
		{
			$this->definition = new ForeignKeyDefinition($name, $columns, $targetTable, $targetColumns);
		}


		/**
		 * @param  string $onUpdateAction
		 * @return static
		 */
		public function setOnUpdateAction($onUpdateAction)
		{
			$this->definition->setOnUpdateAction($onUpdateAction);
			return $this;
		}


		/**
		 * @param  string $onDeleteAction
		 * @return static
		 */
		public function setOnDeleteAction($onDeleteAction)
		{
			$this->definition->setOnDeleteAction($onDeleteAction);
			return $this;
		}


		public function toSql(IDriver $driver)
		{
			return 'ADD ' . $this->definition->toSql($driver);
		}
	}
