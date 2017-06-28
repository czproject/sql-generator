<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class CreateTable implements IStatement
	{
		/** @var string */
		private $tableName;

		/** @var array  [name => ColumnDefinition] */
		private $columns = array();

		/** @var array  [name => IndexDefinition] */
		private $indexes = array();

		/** @var array  [name => ForeignKeyDefinition] */
		private $foreignKeys = array();

		/** @var string|NULL */
		private $comment;

		/** @var array  [name => value] */
		private $options = array();


		/**
		 * @param  string
		 */
		public function __construct($tableName)
		{
			$this->tableName = $tableName;
		}


		/**
		 * @param  string
		 * @param  string
		 * @return ColumnDefinition
		 */
		public function addColumn($name, $type, array $parameters = NULL, array $options = array())
		{
			if (isset($this->columns[$name])) {
				throw new DuplicateException("Column '$name' already exists.");
			}

			return $this->columns[$name] = new ColumnDefinition($name, $type, $parameters, $options);
		}


		/**
		 * @param  string
		 * @param  string
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
		 * @param  string
		 * @param  string[]|string
		 * @param  string
		 * @param  string[]|string
		 * @return ForeignKeyDefinition
		 */
		public function addForeignKey($name, $columns = array(), $targetTable, $targetColumns = array())
		{
			if (isset($this->foreignKeys[$name])) {
				throw new DuplicateException("Foreign key '$name' already exists.");
			}

			return $this->foreignKeys[$name] = new ForeignKeyDefinition($name, $columns, $targetTable, $targetColumns);
		}


		/**
		 * @param  string|NULL
		 * @return self
		 */
		public function setComment($comment)
		{
			$this->comment = $comment;
			return $this;
		}


		/**
		 * @param  string
		 * @param  string
		 * @return self
		 */
		public function setOption($name, $value)
		{
			$this->options[$name] = $value;
			return $this;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			$output = 'CREATE TABLE ' . $driver->escapeIdentifier($this->tableName) . " (\n";

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
				$output .= $optionName . '=' . $optionValue;
			}

			$output .= ';';
			return $output;
		}
	}
