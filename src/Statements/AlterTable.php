<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class AlterTable implements IStatement
	{
		/** @var string */
		private $tableName;

		/** @var IStatement[] */
		private $statements = array();

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
		 * @param  array
		 * @param  array  [name => value]
		 * @return AddColumn
		 */
		public function addColumn($name, $type, array $parameters = NULL, array $options = array())
		{
			return $this->statements[] = new AddColumn($name, $type, $parameters, $options);
		}


		/**
		 * @param  string
		 * @return DropColumn
		 */
		public function dropColumn($column)
		{
			return $this->statements[] = new DropColumn($column);
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  array
		 * @param  array  [name => value]
		 * @return ModifyColumn
		 */
		public function modifyColumn($name, $type, array $parameters = NULL, array $options = array())
		{
			return $this->statements[] = new ModifyColumn($name, $type, $parameters, $options);
		}


		/**
		 * @param  string|NULL
		 * @param  string
		 * @return AddIndex
		 */
		public function addIndex($name = NULL, $type)
		{
			return $this->statements[] = new AddIndex($name, $type);
		}


		/**
		 * @param  string|NULL
		 * @return DropIndex
		 */
		public function dropIndex($index)
		{
			return $this->statements[] = new DropIndex($index);
		}


		/**
		 * @param  string
		 * @param  string[]|string
		 * @param  string
		 * @param  string[]|string
		 * @return AddForeignKey
		 */
		public function addForeignKey($name, $columns = array(), $targetTable, $targetColumns = array())
		{
			return $this->statements[] = new AddForeignKey($name, $columns, $targetTable, $targetColumns);
		}


		/**
		 * @param  string
		 * @return DropForeignKey
		 */
		public function dropForeignKey($foreignKey)
		{
			return $this->statements[] = new DropForeignKey($foreignKey);
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
			if (empty($this->statements) && empty($this->options) && !isset($this->comment)) {
				return '';
			}

			$output = 'ALTER TABLE ' . $driver->escapeIdentifier($this->tableName) . "\n";
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

				$output .= $optionName . '=' . $optionValue;
			}

			$output .= ';';
			return $output;
		}
	}
