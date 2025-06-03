<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Drivers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\NotImplementedException;
	use CzProject\SqlGenerator\IStatement;
	use CzProject\SqlGenerator\TableName;


	class RenameTable implements IStatement
	{
		/** @var string|TableName */
		private $oldTable;

		/** @var string|TableName */
		private $newTable;


		/**
		 * @param  string|TableName $oldTable
		 * @param  string|TableName $newTable
		 */
		public function __construct($oldTable, $newTable)
		{
			$this->oldTable = Helpers::createTableName($oldTable);
			$this->newTable = Helpers::createTableName($newTable);
		}


		public function toSql(IDriver $driver)
		{
			if ($driver instanceof Drivers\MysqlDriver) {
				return 'RENAME TABLE ' . Helpers::escapeTableName($this->oldTable, $driver)
					. ' TO '
					. Helpers::escapeTableName($this->newTable, $driver)
					. ';';
			}

			// see http://stackoverflow.com/questions/886786/how-do-i-rename-the-table-name-using-sql-query
			throw new NotImplementedException('Table rename is not implemented for driver ' . get_class($driver) . '.');
		}
	}
