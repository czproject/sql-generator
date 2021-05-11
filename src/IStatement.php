<?php

	namespace CzProject\SqlGenerator;


	interface IStatement
	{
		/**
		 * @return string
		 */
		function toSql(IDriver $driver);
	}
