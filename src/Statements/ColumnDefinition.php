<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\DuplicateException;
	use CzProject\SqlGenerator\Drivers;
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
		private $parameters = [];

		/** @var array  [name => value] */
		private $options = [];

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
		public function __construct($name, $type, array $parameters = NULL, array $options = [])
		{
			$this->name = $name;
			$this->type = $type;
			$this->parameters = ($parameters !== NULL) ? $parameters : [];
			$this->options = $options;
		}


		/**
		 * @param  bool
		 * @return static
		 */
		public function setNullable($nullable = TRUE)
		{
			$this->nullable = $nullable;
			return $this;
		}


		/**
		 * @param  scalar|NULL
		 * @return static
		 */
		public function setDefaultValue($defaultValue)
		{
			$this->defaultValue = $defaultValue;
			return $this;
		}


		/**
		 * @param  bool
		 * @return static
		 */
		public function setAutoIncrement($autoIncrement = TRUE)
		{
			$this->autoIncrement = $autoIncrement;
			return $this;
		}


		/**
		 * @param  string|NULL
		 * @return static
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
				$parameters = $this->parameters;
				array_walk($parameters, function (&$value) use ($driver) {
					$value = Helpers::formatValue($value, $driver);
				});
				$output .= '(' . implode(', ', $parameters) . ')';
			}

			$options = $this->options;
			$specialOptions = [];

			if ($driver instanceof Drivers\MysqlDriver) {
				$specialOptions = [
					'CHARACTER SET',
					'COLLATE',
				];
			}

			foreach ($specialOptions as $option) {
				if (isset($options[$option])) {
					$output .= ' ' . self::formatOption($option, $options[$option]);
					unset($options[$option]);
				}
			}

			foreach ($options as $option => $value) {
				$output .= ' ' . self::formatOption($option, $value);
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


		/**
		 * @param  string
		 * @param  string|NULL
		 * @return string
		 */
		private static function formatOption($name, $value)
		{
			return $name . ($value !== NULL ? (' ' . $value) : '');
		}
	}
