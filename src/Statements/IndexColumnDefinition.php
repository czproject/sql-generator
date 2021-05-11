<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\OutOfRangeException;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;


	class IndexColumnDefinition implements IStatement
	{
		const ASC = 'ASC';
		const DESC = 'DESC';

		/** @var string */
		private $name;

		/** @var string */
		private $order;

		/** @var int|NULL */
		private $length;


		/**
		 * @param  string $name
		 * @param  string $order
		 * @param  int|NULL $length
		 */
		public function __construct($name, $order = self::ASC, $length = NULL)
		{
			$this->name = $name;
			$this->setOrder($order);
			$this->length = $length;
		}


		/**
		 * @param  string $order
		 * @return static
		 */
		private function setOrder($order)
		{
			$order = (string) $order;

			if ($order !== self::ASC && $order !== self::DESC) {
				throw new OutOfRangeException("Order type '$order' not found.");
			}

			$this->order = $order;
			return $this;
		}


		public function toSql(IDriver $driver)
		{
			$output = $driver->escapeIdentifier($this->name);

			if ($this->length !== NULL) {
				$output .= ' (' . $this->length . ')';
			}

			if ($this->order !== self::ASC) {
				$output .= ' ' . $this->order;
			}

			return $output;
		}
	}
