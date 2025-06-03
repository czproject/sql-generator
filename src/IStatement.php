<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator;


	interface IStatement
	{
		/**
		 * @return string
		 */
		function toSql(IDriver $driver);
	}
