<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator;


	interface IDriver
	{
		/**
		 * @param  string $value
		 * @return string
		 */
		function escapeIdentifier($value);

		/**
		 * @param  string $value
		 * @return string
		 */
		function escapeText($value);

		/**
		 * @param  bool $value
		 * @return string
		 */
		function escapeBool($value);

		/**
		 * @param  string|\DateTime|\DateTimeInterface $value
		 * @return string
		 */
		function escapeDate($value);

		/**
		 * @param  string|\DateTime|\DateTimeInterface $value
		 * @return string
		 */
		function escapeDateTime($value);
	}
