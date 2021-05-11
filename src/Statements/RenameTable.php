<?php

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Drivers;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\NotImplementedException;
	use CzProject\SqlGenerator\IStatement;


	class RenameTable implements IStatement
	{
		/** @var string */
		private $oldTable;

		/** @var string */
		private $newTable;


		/**
		 * @param  string $oldTable
		 * @param  string $newTable
		 */
		public function __construct($oldTable, $newTable)
		{
			$this->oldTable = $oldTable;
			$this->newTable = $newTable;
		}


		public function toSql(IDriver $driver)
		{
			if ($driver instanceof Drivers\MysqlDriver) {
				return 'RENAME TABLE ' . $driver->escapeIdentifier($this->oldTable)
					. ' TO '
					. $driver->escapeIdentifier($this->newTable)
					. ';';
			}

			// see http://stackoverflow.com/questions/886786/how-do-i-rename-the-table-name-using-sql-query
			throw new NotImplementedException('Table rename is not implemented for driver ' . get_class($driver) . '.');
		}
	}
