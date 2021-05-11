<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class AddColumn implements IStatement
	{
		const POSITION_FIRST = TRUE;
		const POSITION_LAST = FALSE;

		/** @var ColumnDefinition */
		private $definition;

		/** @var string|bool */
		private $position = self::POSITION_LAST;


		/**
		 * @param  string $name
		 * @param  string $type
		 * @param  array<int|float|string> $parameters
		 * @param  array<string, string> $options  [name => value]
		 */
		public function __construct($name, $type, array $parameters = NULL, array $options = [])
		{
			$this->definition = new ColumnDefinition($name, $type, $parameters, $options);
		}


		/**
		 * @return static
		 */
		public function moveToFirstPosition()
		{
			$this->position = self::POSITION_FIRST;
			return $this;
		}


		/**
		 * @param  string $column
		 * @return static
		 */
		public function moveAfterColumn($column)
		{
			$this->position = $column;
			return $this;
		}


		/**
		 * @return static
		 */
		public function moveToLastPosition()
		{
			$this->position = self::POSITION_LAST;
			return $this;
		}


		/**
		 * @param  bool $nullable
		 * @return static
		 */
		public function setNullable($nullable = TRUE)
		{
			$this->definition->setNullable($nullable);
			return $this;
		}


		/**
		 * @param  mixed|NULL $defaultValue
		 * @return static
		 */
		public function setDefaultValue($defaultValue)
		{
			$this->definition->setDefaultValue($defaultValue);
			return $this;
		}


		/**
		 * @param  bool $autoIncrement
		 * @return static
		 */
		public function setAutoIncrement($autoIncrement = TRUE)
		{
			$this->definition->setAutoIncrement($autoIncrement);
			return $this;
		}


		/**
		 * @param  string|NULL $comment
		 * @return static
		 */
		public function setComment($comment)
		{
			$this->definition->setComment($comment);
			return $this;
		}


		public function toSql(IDriver $driver)
		{
			$output = 'ADD COLUMN ' . $this->definition->toSql($driver);

			if ($this->position === self::POSITION_FIRST) {
				$output .= ' FIRST';

			} elseif ($this->position !== self::POSITION_LAST) {
				$output .= ' AFTER ' . $driver->escapeIdentifier($this->position);
			}

			return $output;
		}
	}
