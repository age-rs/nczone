<?php

namespace eru\nczone\utility;

use phpbb\db\driver\driver_interface;

class db_util
{
    /**
     * @param driver_interface $db
     * @param array|string $sql
     * @param string $query
     * @return array
     */
    public static function get_rows(driver_interface $db, $sql, $query = 'SELECT'): array
    {
        $queryString = is_string($sql) ? rtrim(rtrim($sql), ';') : $db->sql_build_query($query, $sql);
        $result = $db->sql_query($queryString);
        $rows = $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);
        return $rows ?: [];
    }
    public static function get_num_rows(driver_interface $db, array $sqlArray, $num, $query = 'SELECT'): array
    {
        $result = $db->sql_query_limit($db->sql_build_query($query, $sqlArray), $num);
        $rows = $db->sql_fetchrowset($result);
        $db->sql_freeresult($result);
        return $rows ?: [];
    }

    // todo: build this without getting the whole rowset
    public static function get_col(driver_interface $db, array $sqlArray, $query = 'SELECT'): array
    {
        $col = [];
        foreach (self::get_rows($db, $sqlArray, $query) as $row) {
            $col[] = reset($row);
        }
        return $col;
    }

    /**
     * @param driver_interface $db
     * @param array|string $sql
     * @param string $query
     * @return mixed
     */
    public static function get_row(driver_interface $db, $sql, $query = 'SELECT')
    {
        $queryString = is_string($sql) ? rtrim(rtrim($sql), ';') : $db->sql_build_query($query, $sql);
        $result = $db->sql_query_limit($queryString, 1);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        return $row;
    }

    /**
     * @param driver_interface $db
     * @param array|string $sql
     * @return mixed|null
     */
    public static function get_var(driver_interface $db, $sql)
    {
        // todo: build this without getting the whole row
        $row = self::get_row($db, $sql);
        return $row ? reset($row) : null;
    }

    public static function insert(driver_interface $db, string $table, array $data): string
    {
        $db->sql_query('INSERT INTO ' . $table . ' ' . $db->sql_build_array('INSERT', $data));
        return (string)$db->sql_nextid();
    }

    public static function update(driver_interface $db, string $table, array $sqlArray, $where): void
    {
        if(is_array($where))
        {
            $whereArray = [];
            foreach ($where as $key => $value) {
                $whereArray[] = $key . ' = ' . $value;
            }
            $where = implode(' AND ', $whereArray);
        }
        $db->sql_query('UPDATE ' . $table . ' SET ' . $db->sql_build_array('UPDATE', $sqlArray) .
            (empty($where) ? '' : ' WHERE ' . $where));
    }

}
