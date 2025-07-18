<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;
	use CzProject\SqlGenerator\TableName;
	use CzProject\SqlGenerator\Value;


	class CreateTable implements IStatement
	{
		/** @var string|TableName */
		private $tableName;

		/** @var array<string, ColumnDefinition>  [name => ColumnDefinition] */
		private $columns = [];

		/** @var array<string, IndexDefinition>  [name => IndexDefinition] */
		private $indexes = [];

		/** @var array<string, ForeignKeyDefinition>  [name => ForeignKeyDefinition] */
		private $foreignKeys = [];

		/** @var string|NULL */
		private $comment;

		/** @var array<string, string|Value>  [name => value] */
		private $options = [];


		/**
		 * @param  string|TableName $tableName
		 */
		public function __construct($tableName)
		{
			$this->tableName = Helpers::createTableName($tableName);
		}


		/**
		 * @param  string $name
		 * @param  string $type
		 * @param  array<int|float|string>|NULL $parameters
		 * @param  array<string, string|Value|NULL> $options
		 * @return ColumnDefinition
		 */
		public function addColumn($name, $type, ?array $parameters = NULL, array $options = [])
		{
			if (isset($this->columns[$name])) {
				throw new DuplicateException("Column '$name' already exists.");
			}

			return $this->columns[$name] = new ColumnDefinition($name, $type, $parameters, $options);
		}


		/**
		 * @param  string|NULL $name
		 * @param  string $type
		 * @return IndexDefinition
		 */
		public function addIndex($name, $type)
		{
			if (isset($this->indexes[$name])) {
				throw new DuplicateException("Index '$name' already exists.");
			}

			return $this->indexes[$name] = new IndexDefinition($name, $type);
		}


		/**
		 * @param  string $name
		 * @param  string[]|string $columns
		 * @param  string|TableName $targetTable
		 * @param  string[]|string $targetColumns
		 * @return ForeignKeyDefinition
		 */
		public function addForeignKey($name, $columns, $targetTable, $targetColumns)
		{
			if (isset($this->foreignKeys[$name])) {
				throw new DuplicateException("Foreign key '$name' already exists.");
			}

			return $this->foreignKeys[$name] = new ForeignKeyDefinition($name, $columns, $targetTable, $targetColumns);
		}


		/**
		 * @param  string|NULL $comment
		 * @return static
		 */
		public function setComment($comment)
		{
			$this->comment = $comment;
			return $this;
		}


		/**
		 * @param  string $name
		 * @param  string|Value $value
		 * @return static
		 */
		public function setOption($name, $value)
		{
			$this->options[$name] = $value;
			return $this;
		}


		public function toSql(IDriver $driver)
		{
			$output = 'CREATE TABLE ' . Helpers::escapeTableName($this->tableName, $driver) . " (\n";

			// columns
			$isFirst = TRUE;

			foreach ($this->columns as $column) {
				if ($isFirst) {
					$isFirst = FALSE;

				} else {
					$output .= ",\n";
				}

				$output .= "\t" . $column->toSql($driver);
			}

			foreach ($this->indexes as $index) {
				if ($isFirst) {
					$isFirst = FALSE;

				} else {
					$output .= ",\n";
				}

				$output .= "\t" . $index->toSql($driver);
			}

			foreach ($this->foreignKeys as $foreignKey) {
				if ($isFirst) {
					$isFirst = FALSE;

				} else {
					$output .= ",\n";
				}

				$output .= "\t" . $foreignKey->toSql($driver);
			}

			$output .= "\n)";

			if (isset($this->comment)) {
				$output .= "\n";
				$output .= 'COMMENT ' . $driver->escapeText($this->comment);
			}

			foreach ($this->options as $optionName => $optionValue) {
				$output .= "\n";
				$output .= $optionName . '=' . ($optionValue instanceof Value ? $optionValue->toString($driver) : $optionValue);
			}

			$output .= ';';
			return $output;
		}
	}
