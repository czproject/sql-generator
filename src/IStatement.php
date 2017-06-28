<?php

	namespace CzProject\SqlGenerator;


	interface IStatement
	{
		function toSql(IDriver $driver);
	}
