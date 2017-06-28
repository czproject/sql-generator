<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class ColumnDefinition implements IStatement
	{
		/** @var string */
		private $name;

		/** @var string */
		private $type;

		/** @var array */
		private $parameters = array();

		/** @var array  [name => value] */
		private $options = array();

		/** @var bool */
		private $nullable = FALSE;

		/** @var scalar|NULL */
		private $defaultValue;

		/** @var bool */
		private $autoIncrement = FALSE;

		/** @var string|NULL */
		private $comment;


		/**
		 * @param  string
		 * @param  string
		 * @param  array
		 * @param  array  [name => value]
		 */
		public function __construct($name, $type, array $parameters = NULL, array $options = array())
		{
			$this->name = $name;
			$this->type = $type;
			$this->parameters = ($parameters !== NULL) ? $parameters : array();
			$this->options = $options;
		}


		/**
		 * @param  bool
		 * @return self
		 */
		public function setNullable($nullable = TRUE)
		{
			$this->nullable = $nullable;
			return $this;
		}


		/**
		 * @param  scalar|NULL
		 * @return self
		 */
		public function setDefaultValue($defaultValue)
		{
			$this->defaultValue = $defaultValue;
			return $this;
		}


		/**
		 * @param  bool
		 * @return self
		 */
		public function setAutoIncrement($autoIncrement = TRUE)
		{
			$this->autoIncrement = $autoIncrement;
			return $this;
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
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			$output = $driver->escapeIdentifier($this->name) . ' ' . $this->type;

			if (!empty($this->parameters)) {
				$output .= '(' . implode(', ', $this->parameters) . ')';
			}

			foreach ($this->options as $option => $value) {
				$output .= ' ' . $option;

				if ($value !== NULL) {
					$output .= ' ' . $value;
				}
			}

			$output .= ' ' . ($this->nullable ? 'NULL' : 'NOT NULL');

			if (isset($this->defaultValue)) {
				$output .= ' DEFAULT ' . Helpers::formatValue($this->defaultValue, $driver);
			}

			if ($this->autoIncrement) {
				$output .= ' AUTO_INCREMENT';
			}

			if (isset($this->comment)) {
				$output .= ' COMMENT ' . $driver->escapeText($this->comment);
			}

			return $output;
		}
	}
