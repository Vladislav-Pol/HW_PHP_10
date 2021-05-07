<?php


abstract class Db_PDO
{
    private static $instances = [];

    /**
     * @param $table - имя обрабатываемой таблицы
     * @param null $arSelect - массив возвращаемых полей
     * @param null $arFilter - массив фильтров состоящий из массивов с элементами [field, operation, value], допустимые значения - =, !=, >, >=, <, <=
     * @param null $arOrder - массив массивов для сортировки вида поле->asc|desc
     * @param null[] $arLimit - ограничение выборки параметры offset->, limit->
     */
    public function get($table, $arSelect = [], $arFilter = null, $arOrder = null, $arLimit = ['offset' => 0, 'limit' => null])
    {
        $arTables = $this->getTablesName($this->config['db']['database']);
        if (!in_array($table, $arTables)) {
            return false;
        }

        $arColumns = $this->getTableColumns($this->config['db']['database'], $table);
        if (!array_diff($arSelect, $arColumns) === []) {
            return false;
        }

        $query = "SELECT ";

        if ($arSelect === []) {
            $query .= "* ";
        } else {
            $pref = "";
            foreach ($arSelect as $item) {
                $query .= $pref . $item;
                $pref = ", ";
            }
        }

        $query .= " FROM " . $table;

        if ($arFilter !== null) {
            $query .= " WHERE ";
            $i = 0;
            foreach ($arFilter as $filter) {
                if(in_array($filter[0], $arColumns) && in_array($filter[1], ['=', '!=', '>', '>=', '<', '<='])) {
                    $i++;
                    $query .= $filter[0] . " ";
                    $query .= $filter[1] . " ";
                    $query .= ":filter$i AND ";
                }
            }
            $query = preg_replace('/ AND $/', '', $query);
        }

        if ($arOrder !== null) {
            $query .= " ORDER BY ";
            foreach ($arOrder as $field => $type) {
                if (in_array($field, $arColumns) && in_array($type, ['ASC', 'DESC']))
                    $query .= "$field $type, ";
            }
            $query = preg_replace('/, $/', '', $query);
        }

        if ($arLimit['limit'] !== null) {
            $query .= " LIMIT :offset, :limit";
            $lim = true;
        }

        $stmt = $this->dbh->prepare($query);

        if ($filter) {
            $i = 0;
            foreach ($arFilter as $filter) {
                $i++;
                $stmt->bindParam(":filter$i", $filter[2], PDO::PARAM_STR, 32);
            }
        }

        if ($lim) {
            if (!is_int($arLimit['offset'])) {
                $arLimit['offset'] = 0;
            }
            $stmt->bindParam(':offset', $arLimit['offset'], PDO::PARAM_INT);
            $stmt->bindParam(':limit', $arLimit['limit'], PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    protected function getTablesName($db)
    {
        $arResult = [];
        $query = "SHOW TABLES FROM $db";
        $conn = $this->dbh;

        foreach ($conn->query($query) as $row) {
            $arResult[] = $row[0];
        }

        return $arResult;
    }

    protected function getTableColumns($db, $table)
    {
        $query = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='$db'  AND `TABLE_NAME`='$table';";

        $conn = $this->dbh;

        foreach ($conn->query($query) as $row) {
            $arResult[] = $row[0];
        }
        return $arResult;
    }

    protected function __construct()
    {
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $this->dbh = new PDO('mysql:host=' . $this->config['db']['hostname'] . ';dbname=' . $this->config['db']['database'] . ';port=' . $this->config['db']['port'], $this->config['db']['username'], $this->config['db']['password']);
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {

            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }
}
