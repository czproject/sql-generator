<?php

	namespace CzProject\SqlGenerator;


	class SqlDocument
	{
		/** @var IStatement[] */
		private $statements = array();


		/**
		 * @return self
		 */
		public function addStatement(IStatement $statement)
		{
			$this->statements[] = $statement;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function isEmpty()
		{
			return empty($this->statements);
		}


		/**
		 * @return string[]
		 */
		public function getSqlQueries(IDriver $driver)
		{
			$output = array();

			foreach ($this->statements as $statement) {
				$output[] = $statement->toSql($driver);
			}

			return $output;
		}


		/**
		 * @return string
		 */
		public function toSql(IDriver $driver)
		{
			$output = '';

			foreach ($this->statements as $statement) {
				$output .= "\n";
				$output .= $statement->toSql($driver);
				$output .= "\n";
			}

			return $output;
		}


		/**
		 * @param  string
		 * @param  array
		 * @return Statements\Insert
		 */
		public function insert($tableName, array $data)
		{
			$statement = new Statements\Insert($tableName, $data);
			$this->addStatement($statement);
			return $statement;
		}


		/**
		 * @param  string
		 * @return Statements\CreateTable
		 */
		public function createTable($tableName)
		{
			$statement = new Statements\CreateTable($tableName);
			$this->addStatement($statement);
			return $statement;
		}


		/**
		 * @param  string
		 * @return Statements\DropTable
		 */
		public function dropTable($tableName)
		{
			$statement = new Statements\DropTable($tableName);
			$this->addStatement($statement);
			return $statement;
		}


		/**
		 * @param  string
		 * @param  string
		 * @return Statements\RenameTable
		 */
		public function renameTable($oldTable, $newTable)
		{
			$statement = new Statements\RenameTable($oldTable, $newTable);
			$this->addStatement($statement);
			return $statement;
		}


		/**
		 * @param  string
		 * @return Statements\AlterTable
		 */
		public function alterTable($tableName)
		{
			$statement = new Statements\AlterTable($tableName);
			$this->addStatement($statement);
			return $statement;
		}


		/**
		 * @param  string
		 * @return Statements\SqlCommand
		 */
		public function command($command)
		{
			$statement = new Statements\SqlCommand($command);
			$this->addStatement($statement);
			return $statement;
		}


		/**
		 * @param  string
		 * @return Statements\Comment
		 */
		public function comment($comment)
		{
			$statement = new Statements\Comment($comment);
			$this->addStatement($statement);
			return $statement;
		}
	}
