<?php


abstract class Db
{
    private static $instances = [];

    /**
     * @param $table - имя обрабатываемой таблицы
     * @param null $arSelect - массив возвращаемых полей
     * @param null $arFilter - массив фильтров состоящий из массивов с элементами [field, operation, value]
     * @param null $arOrder - массив массивов для сортировки вида поле->asc|desc
     * @param null[] $arLimit - ограничение выборки параметры offset->, limit->
     */
    public function get($table, $arSelect = null, $arFilter = null, $arOrder = null, $arLimit = ['offset' => 0, 'limit' => null])
    {
        $query = "SELECT ";

        if ($arSelect === null) {
            $query .= "* ";
        } else {
            foreach ($arSelect as $item) {
                $query .= $this->mySqlI->real_escape_string($item) . ", ";
            }
            $query = preg_replace('/, $/', '', $query);
        }

        $query .= " FROM " . $this->mySqlI->real_escape_string($table);

        if ($arFilter !== null){
            $query .= " WHERE ";
            foreach ($arFilter as $filter){
                $query .= $this->mySqlI->real_escape_string($filter[0]) . " ";
                $query .= $this->mySqlI->real_escape_string($filter[1]) . " ";
                $query .= $this->mySqlI->real_escape_string($filter[2]) . " AND ";
            }
            $query = preg_replace('/ AND $/', '', $query);
        }

        if ($arOrder !== null){
            $query .= " ORDER BY ";
            foreach ($arOrder as $field => $type){
                $query .= $this->mySqlI->real_escape_string($field) . " ";
                $query .= $this->mySqlI->real_escape_string($type) . ", ";
            }
            $query = preg_replace('/, $/', '', $query);
        }

        if ($arLimit['limit'] !== null){
            $query .= " LIMIT " . $this->mySqlI->real_escape_string($arLimit['offset'] ?: 0) . ", ";
            $query .= $this->mySqlI->real_escape_string($arLimit['limit']);
        }

        $result = $this->mySqlI->query($query);
        return $data = $result->fetch_all(MYSQLI_ASSOC);
    }

    protected function __construct()
    {
        $this->config = require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $this->mySqlI = new mysqli($this->config['db']['hostname'], $this->config['db']['username'], $this->config['db']['password'], $this->config['db']['database'], $this->config['db']['port']);
    }

    public function __destruct()
    {
        $this->mySqlI->close();
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

