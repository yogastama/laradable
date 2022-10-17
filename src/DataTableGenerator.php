<?php

namespace Yogastama\Laradable;

class DataTableGenerator
{
    private function _generateWhereSqlColumn($request, $value, $operator, $value_field, $prefix = '')
    {
        $sqlWhere = '';
        if ($request->{$value['name'] . $prefix} == '') {
            if (!isset($value['nullable'])) {
                $sqlWhere = " and " . $value['column'] . " is not null";
            }
        } else {
            $sqlWhere = " and " . $value['column'] . " $operator $value_field";
        }
        return $sqlWhere;
    }
    public function filterDataTableWhereSql($params = [
        'columns' => [],
        'request' => []
    ])
    {
        $columns = $params['columns'];
        $request = $params['request'];

        $sqlWhere = '';
        foreach ($columns as $key => $value) {
            switch ($value['type']) {
                case 'like':
                    $sqlWhere .= $this->_generateWhereSqlColumn($request, $value, ' LIKE ', "'%" . $request->{$value['name']} . "%'");
                    break;
                case '=':
                    $sqlWhere .= $this->_generateWhereSqlColumn($request, $value, ' = ', "'" . $request->{$value['name']} . "'");
                    break;
                case 'from_to_number':
                    $sqlWhere .= $this->_generateWhereSqlColumn($request, $value, ' >= ', "'" . $request->{$value['name'] . '_from'} . "'", '_from');
                    $sqlWhere .= $this->_generateWhereSqlColumn($request, $value, ' <= ', "'" . $request->{$value['name'] . '_to'} . "'", '_to');
                    break;
                case 'from_to_date':
                    $sqlWhere .= $this->_generateWhereSqlColumn($request, $value, ' >= ', "'" . tgl_dt($request->{$value['name'] . '_from'}) . "'", '_from');
                    $sqlWhere .= $this->_generateWhereSqlColumn($request, $value, ' <= ', "'" . tgl_dt($request->{$value['name'] . '_to'}) . "'", '_to');
                    break;
                case 'not_where':
                    $sqlWhere .= '';
                    break;

                default:
                    # code...
                    break;
            }
        }
        return $sqlWhere;
    }
    public function orderByDataTableSql($req, $params = [
        'custom_sort' => [
            'newest' => '',
            'oldest' => '',
            'a_to_z' => '',
            'z_to_a' => ''
        ],
        'default_columns' => []
    ])
    {
        $sqlOrder = '';
        if ($req->dt_custom_sort) {
            switch ($req->dt_custom_sort) {
                case 'newest':
                    $sqlOrder .= " ORDER BY " . $params['custom_sort']['newest'] . " DESC";
                    break;
                case 'oldest':
                    $sqlOrder .= " ORDER BY " . $params['custom_sort']['oldest'] . " ASC";
                    break;
                case 'a_to_z':
                    $sqlOrder .= " ORDER BY " . $params['custom_sort']['a_to_z'] . " ASC";
                    break;
                case 'z_to_a':
                    $sqlOrder .= " ORDER BY " . $params['custom_sort']['z_to_a'] . " DESC";
                    break;
            }
        } else {
            $sqlOrder .= " ORDER BY " . $params['default_columns'][$req->order[0]['column']]['column'] . ' ' . $req->order[0]['dir'];
        }
        return $sqlOrder;
    }
    public function getTotalDataSql($total)
    {
        if (empty($total[0])) {
            return 0;
        }
        //* check, apakah pagination lebih dari 1
        if (count($total) > 1) {
            $totalPagination = 0;
            foreach ($total as $key => $value) {
                $totalPagination += $value->total;
            }
            return $totalPagination;
        }
        return $total[0]->total;
    }
    public function getLimitOffsetSql($total)
    {
        $sql_offset = '';
        if ($_POST['length'] != -1) {
            $sql_offset .= " limit " . $_POST['length'] . " offset " .  $_POST['start'];
        } else {
            $sql_offset .= " limit " . $total . " offset " .  $_POST['start'];
        }
        return $sql_offset;
    }
}
