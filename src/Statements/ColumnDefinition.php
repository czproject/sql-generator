<?php

	namespace CzProject\SqlGenerator\Statements;

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

		/** @var array<int|float|string> */
		private $parameters = [];

		/** @var array<string, string>  [name => value] */
		private $options = [];

		/** @var bool */
		private $nullable = FALSE;

		/** @var mixed|NULL */
		private $defaultValue;

		/** @var bool */
		private $autoIncrement = FALSE;

		/** @var string|NULL */
		private $comment;


		/**
		 * @param  string $name
		 * @param  string $type
		 * @param  array<int|float|string>|NULL $parameters
		 * @param  array<string, string> $options  [name => value]
		 */
		public function __construct($name, $type, array $parameters = NULL, array $options = [])
		{
			$this->name = $name;
			$this->type = $type;
			$this->parameters = ($parameters !== NULL) ? $parameters : [];
			$this->options = $options;
		}


		/**
		 * @param  bool $nullable
		 * @return static
		 */
		public function setNullable($nullable = TRUE)
		{
			$this->nullable = $nullable;
			return $this;
		}


		/**
		 * @param  mixed|NULL $defaultValue
		 * @return static
		 */
		public function setDefaultValue($defaultValue)
		{
			$this->defaultValue = $defaultValue;
			return $this;
		}


		/**
		 * @param  bool $autoIncrement
		 * @return static
		 */
		public function setAutoIncrement($autoIncrement = TRUE)
		{
			$this->autoIncrement = $autoIncrement;
			return $this;
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
		 * @param  string $name
		 * @param  string|NULL $value
		 * @return string
		 */
		private static function formatOption($name, $value)
		{
			return $name . ($value !== NULL ? (' ' . $value) : '');
		}
	}
