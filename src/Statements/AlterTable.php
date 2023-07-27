<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;
	use CzProject\SqlGenerator\TableName;
	use CzProject\SqlGenerator\Value;


	class AlterTable implements IStatement
	{
		/** @var string|TableName */
		private $tableName;

		/** @var IStatement[] */
		private $statements = [];

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
		 * @param  array<int|float|string> $parameters
		 * @param  array<string, string|Value|NULL> $options  [name => value]
		 * @return AddColumn
		 */
		public function addColumn($name, $type, array $parameters = NULL, array $options = [])
		{
			return $this->statements[] = new AddColumn($name, $type, $parameters, $options);
		}


		/**
		 * @param  string $column
		 * @return DropColumn
		 */
		public function dropColumn($column)
		{
			return $this->statements[] = new DropColumn($column);
		}


		/**
		 * @param  string $name
		 * @param  string $type
		 * @param  array<int|float|string> $parameters
		 * @param  array<string, string|Value|NULL> $options  [name => value]
		 * @return ModifyColumn
		 */
		public function modifyColumn($name, $type, array $parameters = NULL, array $options = [])
		{
			return $this->statements[] = new ModifyColumn($name, $type, $parameters, $options);
		}


		/**
		 * @param  string|NULL $name
		 * @param  string $type
		 * @return AddIndex
		 */
		public function addIndex($name, $type)
		{
			return $this->statements[] = new AddIndex($name, $type);
		}


		/**
		 * @param  string|NULL $index
		 * @return DropIndex
		 */
		public function dropIndex($index)
		{
			return $this->statements[] = new DropIndex($index);
		}


		/**
		 * @param  string $name
		 * @param  string[]|string $columns
		 * @param  string|TableName $targetTable
		 * @param  string[]|string $targetColumns
		 * @return AddForeignKey
		 */
		public function addForeignKey($name, $columns, $targetTable, $targetColumns)
		{
			return $this->statements[] = new AddForeignKey($name, $columns, $targetTable, $targetColumns);
		}


		/**
		 * @param  string $foreignKey
		 * @return DropForeignKey
		 */
		public function dropForeignKey($foreignKey)
		{
			return $this->statements[] = new DropForeignKey($foreignKey);
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
			if (empty($this->statements) && empty($this->options) && !isset($this->comment)) {
				return '';
			}

			$output = 'ALTER TABLE ' . Helpers::escapeTableName($this->tableName, $driver) . "\n";
			$isFirst = TRUE;

			foreach ($this->statements as $statement) {
				if ($isFirst) {
					$isFirst = FALSE;

				} else {
					$output .= ",\n";
				}

				$output .= $statement->toSql($driver);
			}

			if (isset($this->comment)) {
				if ($isFirst) {
					$isFirst = FALSE;

				} else {
					$output .= ",\n";
				}

				$output .= 'COMMENT ' . $driver->escapeText($this->comment);
			}

			foreach ($this->options as $optionName => $optionValue) {
				if ($isFirst) {
					$isFirst = FALSE;

				} else {
					$output .= ",\n";
				}

				$output .= $optionName . '=' . ($optionValue instanceof Value ? $optionValue->toString($driver) : $optionValue);
			}

			$output .= ';';
			return $output;
		}
	}
