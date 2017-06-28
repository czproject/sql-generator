<?php

	namespace CzProject\SqlGenerator;


	interface IDriver
	{
		/**
		 * @param  string
		 * @return string
		 */
		function escapeIdentifier($value);

		/**
		 * @param  string
		 * @return string
		 */
		function escapeText($value);

		/**
		 * @param  bool
		 * @return string
		 */
		function escapeBool($value);

		/**
		 * @param  string|\DateTime
		 * @return string
		 */
		function escapeDate($value);

		/**
		 * @param  string|\DateTime
		 * @return string
		 */
		function escapeDateTime($value);
	}
