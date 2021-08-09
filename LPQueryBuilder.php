<?php

class LPQueryBuilder {

	private static $table;
	private static $select;
	private static $selectColumns;
	private static $selectCount;
	private static $insert;
	private static $update;
	private static $delete;
	private static $join;
	private static $where;
	private static $groupBy;
	private static $having;
	private static $orderBy;
	private static $offset;

	private static $sql;
	private static $sqlCount;

	public static function table(string $tableName) {
		self::$table = null;
		self::$select = null;
		self::$selectColumns = null;
		self::$selectCount = null;
		self::$insert = null;
		self::$update = null;
		self::$delete = null;
		self::$join = null;
		self::$where = null;
		self::$groupBy = null;
		self::$having = null;
		self::$orderBy = null;
		self::$offset = null;
		self::$sql = null;
		self::$sqlCount = null;

		self::$table = $tableName;
		return new self;
	}

	public static function join(string $join) {
		self::$join .= 'INNER JOIN ' . $join . ' ';
		return new self;
	}

	public static function leftJoin(string $leftJoin) {
		self::$join .= 'LEFT JOIN ' . $leftJoin . ' ';
		return new self;
	}

	public static function where(... $where) {
		self::$where = 'WHERE 1=1 ';		
		self::$where .=  count($where) > 0 ? 'AND ' . implode(' AND ', $where) . ' ' : '';
		return new self;
	}

	public static function orderBy(... $orderBy) {
		self::$orderBy = 'ORDER BY ' . implode(', ', $orderBy);
		return new self;
	}

	public static function groupBy(... $groupBy) {
		self::$groupBy = 'GROUP BY ' . implode(', ', $groupBy);
		return new self;
	}

	public static function having($having) {
		self::$having = 'HAVING ' . $having;
		return new self;
	}

	public static function paginate($size, $page) {
		self::$offset = "
			OFFSET {$size} * ({$page} - 1) ROWS 
      FETCH NEXT {$size} ROWS ONLY
    ";
		return new self;
	}

	public static function select(... $arrProperties) {
		self::$selectColumns = implode(', ', $arrProperties);		
		return new self;
	}

	private static function makeSelect() {
		self::$selectCount = "
			SELECT COUNT(*) AS totalRecords FROM (
        SELECT " . self::$selectColumns . "
          FROM " . self::$table . "
        " . self::$join . "
        " . self::$where . "
        " . self::$groupBy . "
      ) AS count
		";

		self::$select = "
      SELECT " . self::$selectColumns . "
        FROM " . self::$table . "
      " . self::$join . "
      " . self::$where . "
      " . self::$groupBy . "
      " . self::$having . "
      " . self::$orderBy . "
      " . self::$offset . "
		";

		return new self;
	}	

	public static function insert($arrProperties) {
		self::$insert = 'INSERT INTO ' . self::$table . '(';
		self::$insert .= implode(', ', array_keys($arrProperties)) . ") VALUES ('";
		self::$insert .= implode("', '", array_values($arrProperties)) . "')";
		return new self;
	}

	public static function update($arrProperties) {
		self::$update = 'UPDATE ' . self::$table . ' SET  ';
		foreach ($arrProperties as $key => $value) {
			self::$update .= $key . " = '" . $value . "',";
		}
		self::$update .= str_replace(['1=1 AND'], [''], self::$where);
		return new self;
	}

	public static function delete() {
		self::$delete = 'DELETE FROM ' . self::$table . ' ';
		self::$delete .= str_replace(['1=1 AND'], [''], self::$where);
		return new self;
	}

	public static function sql() {
		if(self::$selectColumns){ self::makeSelect(); return self::$select; }
		else if(self::$insert){ return self::$insert; }
		else if(self::$update){ return self::$update; }
		else if(self::$delete){ return self::$delete; }
	}

	public static function sqlCount() {
		self::sql();
		return self::$selectCount;
	}
}