<?php

	namespace CzProject\SqlGenerator;


	class Value
	{
		/** @var scalar|\Stringable|\DateTimeInterface */
		private $value;


		/**
		 * @param scalar|\Stringable|\DateTimeInterface $value
		 */
		public function __construct($value)
		{
			$this->value = $value;
		}


		/**
		 * @return string
		 */
		public function toString(IDriver $driver)
		{
			return Helpers::formatValue($this->value, $driver);
		}


		/**
		 * @param  scalar|\Stringable|\DateTimeInterface $value
		 * @return self
		 */
		public static function create($value)
		{
			return new self($value);
		}
	}
