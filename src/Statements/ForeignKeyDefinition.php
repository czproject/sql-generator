<?php

	declare(strict_types=1);

	namespace CzProject\SqlGenerator\Statements;

	use CzProject\SqlGenerator\Helpers;
	use CzProject\SqlGenerator\OutOfRangeException;
	use CzProject\SqlGenerator\IDriver;
	use CzProject\SqlGenerator\IStatement;
	use CzProject\SqlGenerator\TableName;


	class ForeignKeyDefinition implements IStatement
	{
		const ACTION_RESTRICT = 'RESTRICT';
		const ACTION_NO_ACTION = 'NO ACTION';
		const ACTION_CASCADE = 'CASCADE';
		const ACTION_SET_NULL = 'SET NULL';

		/** @var string */
		private $name;

		/** @var string[] */
		private $columns;

		/** @var string|TableName */
		private $targetTable;

		/** @var string[] */
		private $targetColumns;

		/** @var string */
		private $onUpdateAction = self::ACTION_RESTRICT;

		/** @var string */
		private $onDeleteAction = self::ACTION_RESTRICT;


		/**
		 * @param  string $name
		 * @param  string[]|string $columns
		 * @param  string|TableName $targetTable
		 * @param  string[]|string $targetColumns
		 */
		public function __construct($name, $columns, $targetTable, $targetColumns)
		{
			$this->name = $name;
			$this->targetTable = Helpers::createTableName($targetTable);

			if (!is_array($columns)) {
				$columns = [$columns];
			}

			foreach ($columns as $column) {
				$this->columns[] = $column;
			}

			if (!is_array($targetColumns)) {
				$targetColumns = [$targetColumns];
			}

			foreach ($targetColumns as $targetColumn) {
				$this->targetColumns[] = $targetColumn;
			}
		}


		/**
		 * @param  string $onUpdateAction
		 * @return static
		 */
		public function setOnUpdateAction($onUpdateAction)
		{
			if (!$this->validateAction($onUpdateAction)) {
				throw new OutOfRangeException("Action '$onUpdateAction' is invalid.");
			}

			$this->onUpdateAction = $onUpdateAction;
			return $this;
		}


		/**
		 * @param  string $onDeleteAction
		 * @return static
		 */
		public function setOnDeleteAction($onDeleteAction)
		{
			if (!$this->validateAction($onDeleteAction)) {
				throw new OutOfRangeException("Action '$onDeleteAction' is invalid.");
			}

			$this->onDeleteAction = $onDeleteAction;
			return $this;
		}


		public function toSql(IDriver $driver)
		{
			$output = 'CONSTRAINT ' . $driver->escapeIdentifier($this->name);
			$output .= ' FOREIGN KEY (';
			$output .= implode(', ', array_map([$driver, 'escapeIdentifier'], $this->columns));
			$output .= ') REFERENCES ' . Helpers::escapeTableName($this->targetTable, $driver) . ' (';
			$output .= implode(', ', array_map([$driver, 'escapeIdentifier'], $this->targetColumns));
			$output .= ')';
			$output .= ' ON DELETE ' . $this->onDeleteAction;
			$output .= ' ON UPDATE ' . $this->onUpdateAction;
			return $output;
		}


		/**
		 * @param  string $action
		 * @return bool
		 */
		private function validateAction($action)
		{
			return $action === self::ACTION_RESTRICT
				|| $action === self::ACTION_NO_ACTION
				|| $action === self::ACTION_CASCADE
				|| $action === self::ACTION_SET_NULL;
		}
	}
