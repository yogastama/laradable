<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


function boks_table($data, $class, $id)
{
    $autowidth = "";

    $i = 1;
    foreach ($data as $k => $v) {
        if (in_array('datatable-autowidth', $v)) {
            $autowidth = " datatable-autowidth-$i";
        }
        $i++;
    }

    $output = "<table class='$class $autowidth' id='$id' style='width:100%;box-sizing: border-box;' data-init-plugin='dataTables'>";
    $output .= boks_table_thead($data);
    $output .= boks_table_tfoot($data);
    $output .= "<tbody></tbody>";
    $output .= "</table>";
    return $output;
}

function boks_table_thead($array)
{
    $output = '<thead><tr>';
    foreach ($array as $key => $value) {
        if (!in_array('no-table', $value)) {
            if (array_key_exists('title', $value)) {
                $output .= "<th style='text-align:center;'>" . $value['title'] . "</th>";
            } else {
                $judul = get_judul($key);
                $output .= "<th style='text-align:center;'>$judul</th>";
            }
        }
    }

    $output .= "</tr></thead>";
    return $output;
}

function get_judul($data)
{
    $df = explode('_', $data);
    $dg = array_pop($df);
    return implode(' ', $df);
}

function boks_table_tfoot($column_search)
{
    $output = '<thead><tr>';
    foreach ($column_search as $key => $value) {
        if (!in_array('no-table', $value)) {
            if (array_key_exists('type', $value)) {
                if ($value['type'] == 'like' || $value['type'] == 'file') {
                    $output .= "<th>";
                    if (array_key_exists('sub-type', $value)) {
                        if ($value['sub-type'] == 'date' || $value['sub-type'] == 'datetime') {
                            $output .= boks_input([
                                "name" => $key,
                                "class" => "form-control input-sm",
                                "attr" => ["placeholder" => "Tanggal", 'autocomplete' => 'off'],
                            ]);
                        } elseif ($value['sub-type'] == 'year') {
                            $output .= boks_input([
                                "name" => $key,
                                "class" => "form-control input-sm",
                                "attr" => ["placeholder" => "Tahun", 'autocomplete' => 'off']
                            ]);
                        } elseif ($value['sub-type'] == 'time') {
                            $output .= boks_input([
                                "name" => $key,
                                "class" => "form-control input-sm",
                                "attr" => ["placeholder" => "Jam", 'autocomplete' => 'off']
                            ]);
                        } else {
                            $output .= boks_input([
                                "name" => $key
                            ]);
                        }
                    } else {
                        $output .= boks_input([
                            "name" => $key,
                            "class" => "form-control input-sm",
                            'attr' => ['autocomplete' => 'off']
                        ]);
                    }
                    $output .= "</th>";
                } elseif ($value['type'] == 'from') {
                    $output .= "<th>";
                    if (array_key_exists('sub-type', $value)) {
                        if ($value['sub-type'] == 'number') {
                            if (array_key_exists('field', $value)) {
                                $output .= boks_input([
                                    "name" => $value['field'][0],
                                    "class" => "form-control input-sm angka mb-1",
                                    "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                                ]);
                                $output .= boks_input([
                                    "name" => $value['field'][1],
                                    "class" => "form-control input-sm angka",
                                    "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                                ]);
                            } else {
                                $output .= boks_input([
                                    "name" => $key . '_from',
                                    "class" => "form-control input-sm angka mb-1",
                                    "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                                ]);
                                $output .= boks_input([
                                    "name" => $key . '_to',
                                    "class" => "form-control input-sm angka",
                                    "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                                ]);
                            }
                        } elseif ($value['sub-type'] == 'date') {
                            if (array_key_exists('field', $value)) {
                                $output .= boks_input([
                                    "name" => $value['field'][0],
                                    "class" => "form-control input-sm datep mb-1",
                                    "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                                ]);
                                $output .= boks_input([
                                    "name" => $value['field'][1],
                                    "class" => "form-control input-sm datep",
                                    "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                                ]);
                            } else {
                                $output .= boks_input([
                                    "name" => $key . '_from',
                                    "class" => "form-control input-sm datep mb-1",
                                    "attr" => ["placeholder" => "From"]
                                ]);
                                $output .= boks_input([
                                    "name" => $key . '_to',
                                    "class" => "form-control input-sm datep",
                                    "attr" => ["placeholder" => "To"]
                                ]);
                            }
                        } elseif ($value['sub-type'] == 'datetime') {
                            if (array_key_exists('field', $value)) {
                                $output .= boks_input([
                                    "name" => $value['field'][0],
                                    "class" => "form-control input-sm datetimep mb-1",
                                    "attr" => ["placeholder" => "From"]
                                ]);
                                $output .= boks_input([
                                    "name" => $value['field'][1],
                                    "class" => "form-control input-sm datetimep",
                                    "attr" => ["placeholder" => "To"]
                                ]);
                            } else {
                                $output .= boks_input([
                                    "name" => $key . '_from',
                                    "class" => "form-control input-sm datetimep mb-1",
                                    "attr" => ["placeholder" => "From"]
                                ]);
                                $output .= boks_input([
                                    "name" => $key . '_to',
                                    "class" => "form-control input-sm datetimep",
                                    "attr" => ["placeholder" => "To"]
                                ]);
                            }
                        } elseif ($value['sub-type'] == 'time') {
                            if (array_key_exists('field', $value)) {
                                $output .= boks_input([
                                    "name" => $value['field'][0],
                                    "class" => "form-control input-sm timep mb-1",
                                    "attr" => ["placeholder" => "From"]
                                ]);
                                $output .= boks_input([
                                    "name" => $value['field'][1],
                                    "class" => "form-control input-sm timep",
                                    "attr" => ["placeholder" => "To"]
                                ]);
                            } else {
                                $output .= boks_input([
                                    "name" => $key . '_from',
                                    "class" => "form-control input-sm timep mb-1",
                                    "attr" => ["placeholder" => "From"]
                                ]);
                                $output .= boks_input([
                                    "name" => $key . '_to',
                                    "class" => "form-control input-sm timep",
                                    "attr" => ["placeholder" => "To"]
                                ]);
                            }
                        } elseif ($value['sub-type'] == 'year') {
                            if (array_key_exists('field', $value)) {
                                $output .= boks_input([
                                    "name" => $value['field'][0],
                                    "class" => "form-control input-sm yearp mb-1",
                                    "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                                ]);
                                $output .= boks_input([
                                    "name" => $value['field'][1],
                                    "class" => "form-control input-sm yearp",
                                    "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                                ]);
                            } else {
                                $output .= boks_input([
                                    "name" => $key . '_from',
                                    "class" => "form-control input-sm yearp mb-1",
                                    "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                                ]);
                                $output .= boks_input([
                                    "name" => $key . '_to',
                                    "class" => "form-control input-sm yearp",
                                    "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                                ]);
                            }
                        } else {
                            $output .= boks_input([
                                "name" => $key . '_from',
                                "class" => "form-control input-sm mb-1",
                                "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                            ]);
                            $output .= boks_input([
                                "name" => $key . '_to',
                                "class" => "form-control input-sm",
                                "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                            ]);
                        }
                    } else {
                        $output .= boks_input([
                            "name" => $key . '_from',
                            "class" => "form-control input-sm angka mb-1",
                            "attr" => ["placeholder" => "From", 'autocomplete' => 'off']
                        ]);
                        $output .= boks_input([
                            "name" => $key . '_to',
                            "class" => "form-control input-sm angka",
                            "attr" => ["placeholder" => "To", 'autocomplete' => 'off']
                        ]);
                    }
                    $output .= "</th>";
                } elseif ($value['type'] == 'date' || $value['type'] == 'datetime') {
                    $output .= "<th>";

                    $output .= boks_input([
                        "name" => $key,
                        "class" => "form-control input-sm datep",
                        "attr" => ["placeholder" => "Tanggal", 'autocomplete' => 'off']
                    ]);

                    $output .= "</th>";
                } elseif ($value['type'] == 'option') {
                    $output .= "<th>" . boks_select([
                        'class' => "form-control input-sm",
                        'name' => $key,
                        'options' => $value['data'],
                        'first' => ['' => "All"],
                        'attr' => ["data-init-plugin" => "select2", 'autocomplete' => 'off']
                    ]) . "</th>";
                } else {
                    $output .= "<th></th>";
                }
            }
        }
    }
    $output .= '</tr></thead>';

    return $output;
}

// buat eksekusi datanya
function dt_get($table_name, $query = [])
{
    // $join = [], $column_search = [], $where = [], $like = [], $group_by = [], $or_like = [], $or_where = [], $order = '', $urut = 'desc'
    $table = dt_query($table_name, $query);
    if ($_POST['length'] != -1) {
        $table->skip($_POST['start'])
            ->take($_POST['length']);
    }
    // dd($table->toSql());
    return $table->get();
}
// buat query tabelnya
function dt_query($table_name, $query = [])
{
    $table = DB::table($table_name);


    if (isset($query['select'])) {
        $table->select($query['select']);
    }

    if (isset($query['select_raw'])) {
        $table->selectRaw($query['select_raw']);
    }

    if (isset($query['is_distinct']) && $query['is_distinct']) {
        $table->distinct();
    }

    if (Schema::hasColumn($table_name, 'deleted_at')) {
        if (!isset($query['mode'])) {
            $table->where("$table_name.deleted_at", '=', null);
        } elseif ($query['mode'] == 'trash') {
            $table->where("$table_name.deleted_at", '!=', null);
        }
    }

    if (array_key_exists('my_query', $query)) {
        $query['my_query']($table);
    }

    if (array_key_exists("where", $query)) {
        foreach ($query["where"] as $wk => $wv) {
            if (is_array($wv)) {
                if ($wv[0] == '!=' && $wv[1] == null) {
                    $table->whereNotNull($wk);
                } else {
                    $table->where($wk, $wv[0], $wv[1]);
                }
            } else {
                $table->where($wk, $wv);
            }
        }
    }

    if (array_key_exists("where_auth", $query)) {
        foreach ($query["where_auth"] as $wk => $wv) {
            if (is_array($wv)) {
                $arrAuth = array_filter(explode(".", $wv[1]));
                $user = auth()->user();
                if (count($arrAuth) > 1) {

                    foreach ($arrAuth as $aa) {
                        $user = $user->{$aa};
                    }
                } else {
                    $user = $user->{$wv[1]};
                }

                $table->where($wk, $wv[0], $user);
            } else {
                $arrAuth = array_filter(explode(".", $wv));
                $user = auth()->user();
                if (count($arrAuth) > 1) {
                    foreach ($arrAuth as $aa) {
                        $user = $user->{$aa};
                    }
                } else {
                    $user = $user->{$wv};
                }

                $table->where($wk, $user);
            }
        }
    }

    if (isset($query['where_raw'])) {
        $table->whereRaw(DB::raw($query['where_raw']));
    }
    if (array_key_exists("where_not", $query)) {
        foreach ($query['where_not'] as $kwn => $vwn) {
            $table->where($kwn, '!=', $vwn);
        }
    }
    if (isset($query['having_raw'])) {
        if (is_array($query['having_raw'])) {
            foreach ($query['having_raw'] as $hrk => $hrv) {
                $table->havingRaw($hrv);
            }
        } else {
            $table->havingRaw($query['having_raw']);
        }
    }

    if (array_key_exists('join', $query)) {
        foreach ($query['join'] as $jk => $jv) {
            $jd = explode(' ', $jv);
            $table->leftJoin($jk, $jd[0], $jd[1], $jd[2]);
        }
    }

    if (array_key_exists('join_raw', $query)) {
        foreach ($query['join_raw'] as $jk => $jv) {
            $table->leftJoin($jk, function ($join) use ($jv) {
                $join->on(DB::raw($jv));
            });
        }
    }

    if (array_key_exists('join_raw_2', $query)) {
        foreach ($query['join_raw_2'] as $jk => $jv) {
            $table->leftJoin(DB::raw($jk), function ($join) use ($jv) {
                $join->on(DB::raw($jv));
            });
        }
    }

    if (array_key_exists("group_by", $query)) {
        $table->groupBy($query["group_by"]);
    }

    if (array_key_exists("or_where", $query)) {
        $table->orWhere($query["or_where"]);
    }
    if (array_key_exists("order", $query)) {
        $table->orderBy("$table_name." . array_keys(($query["order"]))[0], array_values($query["order"])[0]);
    }

    if (array_key_exists("where_in", $query)) {
        foreach ($query['where_in'] as $wik => $wiv) {
            $table->whereIn($wik, $wiv);
        }
    }

    if (array_key_exists("column_search", $query)) {
        foreach ($query['column_search'] as $c => $k) {
            if (!in_array("no-table", $k)) {
                if ($k['type'] == 'like') {
                    if ($_POST[$c] != '') {
                        $table->where($c, 'like', '%' . $_POST[$c] . '%');
                    }
                } elseif ($k['type'] == 'option') {
                    if ($_POST[$c] != '') {
                        if (array_key_exists('join', $k)) {
                            $table->where($k['join'], $_POST[$c]);
                        } else {
                            if ($_POST[$c] != '') {
                                $table->where($c, $_POST[$c]);
                            }
                        }
                    }
                } elseif ($k['type'] == 'date' || $k['type'] == 'datetime') {
                    if ($_POST[$c] != '') {
                        $table->where($c, tgl_dt($_POST[$c]));
                    }
                } elseif ($k['type'] == 'from') {
                    if (array_key_exists('sub-type', $k)) {
                        if ($k['sub-type'] == 'number') {
                            if (array_key_exists('field', $k)) {
                                if ($_POST[$k['field'][0]] != '') {
                                    $table->where($c, '>=', $_POST[$k['field'][0]]);
                                }

                                if ($_POST[$k['field'][1]] != '') {
                                    $table->where($c, '<=', $_POST[$k['field'][1]]);
                                }
                            } else {
                                if ($_POST[$c . '_from'] != '') {
                                    $table->where($c, '>=', $_POST[$c . '_from']);
                                }

                                if ($_POST[$c . '_to'] != '') {
                                    $table->where($c, '<=', $_POST[$c . '_to']);
                                }
                            }
                        } elseif ($k['sub-type'] == 'date' || $k['sub-type'] == 'datetime') {
                            if (array_key_exists('field', $k)) {
                                if ($_POST[$k['field'][0]] != '') {
                                    $table->where($c . '>=', tgl_dt($_POST[$k['field'][0]]));
                                }

                                if ($_POST[$k['field'][1]] != '') {
                                    $table->where($c, '<=', tgl_dt($_POST[$k['field'][1]]));
                                }
                            } else {
                                if ($_POST[$c . '_from'] != '') {
                                    $table->where($c, '>=', tgl_dt($_POST[$c . '_from']));
                                }

                                if ($_POST[$c . '_to'] != '') {
                                    $table->where($c, '<=', tgl_dt($_POST[$c . '_to']));
                                }
                            }
                        } elseif ($k['sub-type'] == 'time') {
                            if (array_key_exists('field', $k)) {
                                if ($_POST[$k['field'][0]] != '') {
                                    $table->where($c . '>=', $_POST[$k['field'][0]]);
                                }

                                if ($_POST[$k['field'][1]] != '') {
                                    $table->where($c, '<=', $_POST[$k['field'][1]]);
                                }
                            } else {
                                if ($_POST[$c . '_from'] != '') {
                                    $table->where($c, '>=', $_POST[$c . '_from']);
                                }

                                if ($_POST[$c . '_to'] != '') {
                                    $table->where($c, '<=', $_POST[$c . '_to']);
                                }
                            }
                        }
                    } else {
                        if ($_POST[$c . '_from'] != '') {
                            $table->where($c, '>=', $_POST[$c . '_from']);
                        }

                        if ($_POST[$c . '_to'] != '') {
                            $table->where($c, '<=', $_POST[$c . '_to']);
                        }
                    }
                }
            }
        }
    }

    $column_order = get_clean_array($query['column_search']);

    if (isset($_POST['order'])) {
        $table->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    }

    return $table;
}
// buat hitung total datanya
function dt_count_all($table_name, $query = [])
{
    $table = DB::table($table_name);

    if (Schema::hasColumn($table_name, 'deleted_at')) {
        if (!isset($query['mode'])) {
            $table->where("$table_name.deleted_at", '=', null);
        } elseif ($query['mode'] == 'trash') {
            $table->where("$table_name.deleted_at", '!=', null);
        }
    }

    if (isset($query['select'])) {
        $table->select($query['select']);
    }

    if (isset($query['select_raw'])) {
        $table->selectRaw($query['select_raw']);
    }

    if (isset($query['is_distinct']) && $query['is_distinct']) {
        $table->distinct();
    }

    if (array_key_exists('my_query', $query)) {
        $query['my_query']($table);
    }

    if (array_key_exists("where", $query)) {
        foreach ($query["where"] as $wk => $wv) {
            if (is_array($wv)) {
                $table->where($wk, $wv[0], $wv[1]);
            } else {
                $table->where($wk, $wv);
            }
        }
    }

    if (isset($query['where_raw'])) {
        $table->whereRaw(DB::raw($query['where_raw']));
    }

    if (array_key_exists("where_not", $query)) {
        foreach ($query['where_not'] as $kwn => $vwn) {
            $table->where($kwn, '!=', $vwn);
        }
    }

    if (array_key_exists("where_in", $query)) {
        foreach ($query['where_in'] as $wik => $wiv) {
            $table->whereIn($wik, $wiv);
        }
    }

    if (array_key_exists("where_auth", $query)) {
        foreach ($query["where_auth"] as $wk => $wv) {
            if (is_array($wv)) {
                $arrAuth = array_filter(explode(".", $wv[1]));
                $user = auth()->user();
                if (count($arrAuth) > 1) {

                    foreach ($arrAuth as $aa) {
                        $user = $user->{$aa};
                    }
                } else {
                    $user = $user->{$wv[1]};
                }

                $table->where($wk, $wv[0], $user);
            } else {
                $arrAuth = array_filter(explode(".", $wv));
                $user = auth()->user();
                if (count($arrAuth) > 1) {
                    foreach ($arrAuth as $aa) {
                        $user = $user->{$aa};
                    }
                } else {
                    $user = $user->{$wv};
                }

                $table->where($wk, $user);
            }
        }
    }


    if (array_key_exists('where_not_in', $query)) {
        $table->whereNotIn(array_keys($query['where_not_in'])[0], array_values($query['where_not_in'])[0]);
    }

    if (array_key_exists('join', $query)) {
        foreach ($query['join'] as $jk => $jv) {
            $jd = explode(' ', $jv);
            $table->leftJoin($jk, $jd[0], $jd[1], $jd[2]);
        }
    }

    if (array_key_exists('join_raw', $query)) {
        foreach ($query['join_raw'] as $jk => $jv) {
            $table->leftJoin($jk, function ($join) use ($jv) {
                $join->on(DB::raw($jv));
            });
        }
    }

    if (array_key_exists("group_by", $query)) {
        $table->groupBy($query["group_by"]);
    }

    if (array_key_exists("or_where", $query)) {
        $table->orWhere($query["or_where"]);
    }

    if (isset($query['having_raw']) || isset($query['select_raw'])) {
        return $table->get()->count();
    }
    return $table->count();
}
// buat hitung total yang diquery
function dt_count_filtered($table_name, $query = [])
{
    $table = dt_query($table_name, $query);
    if (isset($query['having_raw']) || isset($query['select_raw'])) {
        return $table->get()->count();
    }
    return $table->count();
}
function dja_row($col, $list)
{
    $data = [];

    foreach ($list as $ls) {
        $row = [];

        foreach ($col as $key => $val) {
            if (!in_array('no-table', $val)) {
                if (array_key_exists('display', $val)) {
                    $attr = '';
                    if (array_key_exists('attr', $val['display'])) {
                        if (is_array($val['display']['attr'])) {
                            foreach ($val['display']['attr'] as $d) {
                                $attr .= $d . " ";
                            }
                        } else {
                            $attr = $val['display']['attr'] . " ";
                        }
                    }

                    $link = '';
                    if (array_key_exists('id', $val['display'])) {
                        if (is_array($val['display']['id'])) {
                            $last = end($val['display']['id']);
                            foreach ($val['display']['id'] as $d) {
                                $link .= $ls->$d;

                                if ($last != $d) {
                                    $link .= "/";
                                }
                            }
                        } else {
                            $id_data = $val['display']['id'];
                            $link .= $ls->$id_data;
                        }
                    }

                    if (array_key_exists('end_link', $val['display'])) {
                        if (is_array($val['display']['end_link'])) {
                            foreach ($val['display']['end_link'] as $el) {
                                $link .= "/";
                                $link .= $el;
                            }
                        } else {
                            $link .= "/";
                            $link .= $val['display']['end_link'];
                        }
                    }

                    if (in_array("my-modal", $val['display'])) {
                        $attr .= "class='but-modal'";
                    }
                    $text_right = '';
                    if ($val['type'] == 'from') {
                        $text_right = ' style="text-align:right;" ';
                    } else {
                        $text_right = '';
                    }
                    if (isset($val['display']['align']) && $val['display']['align'] == 'right') {
                        $text_right = ' style="text-align:right;"';
                    }
                    if (array_key_exists('type', $val['display'])) {

                        // #LINK
                        if ($val['display']['type'] == 'link') {
                            if (in_array("specialchars", $val)) {
                                $row[] = "<a $text_right href='" . url($val['display']['link'] . $link) . "' $attr>" . $ls->$key . "</a>";
                            } else {
                                $row[] = "<a $text_right href='" . url($val['display']['link'] . $link) . "' $attr>" . htmlspecialchars($ls->$key) . "</a>";
                            }
                        } elseif ($val['display']['type'] == 'link_image') {
                            $row[] = "<a href='" . url($val['display']['link'] . $link) . "' $attr class='d-flex flex-wrap align-items-center'><img height='35px' class='m-r-10 m-t-5 m-b-5' src='" . $ls->$key . "'>" . $ls->$key . "</a>";
                            // #LINK OPTION
                        } elseif ($val['display']['type'] == 'button-action') {
                            $id_data = $val['display']['id'];
                            $row[] = "<a href='" . url($val['display']['link'] . $link) . "' " . $val['display']['button']['attr'] . " class='btn btn-" . $val['display']['button']['type'] . " " . $val['display']['button']['class'] . "' data-id='" . $ls->$id_data . "'>" . $val['display']['button']['content'] . "</a>";
                            // #LINK OPTION
                        } elseif ($val['display']['type'] == 'link_option') {
                            if (array_key_exists($ls->$key, $val['data'])) {
                                if (array_key_exists('link', $val['display'])) {
                                    $row[] = "<a href='" . url($val['display']['link'] . $link) . "' $attr>" . $val['data'][$ls->$key] . "</a>";
                                } else {
                                    $row[] = $val['data'][$ls->$key];
                                }
                            } else {
                                $row[] = 'Tidak diketahui';
                            }
                        } elseif ($val['display']['type'] == 'link_date') {
                            if ($ls->$key == '0000-00-00') {
                                $row[] = '';
                            } else {
                                $row[] = "<a $text_right href='" . url($val['display']['link'] . $link) . "' $attr>" .  tgl_indo($ls->$key) . "</a>";
                            }
                            // #OPTION
                        } elseif ($val['display']['type'] == 'link_date_dmy') {
                            if ($ls->$key == '0000-00-00') {
                                $row[] = '';
                            } else {
                                $row[] = "<a $text_right href='" . url($val['display']['link'] . $link) . "' $attr>" .  tgl_indo($ls->$key, 'angka', true, false) . "</a>";
                            }
                            // #OPTION
                        } elseif ($val['display']['type'] == 'link_date_time') {
                            if ($ls->$key == '0000-00-00') {
                                $row[] = '';
                            } else {
                                if (array_key_exists('link', $val['display'])) {
                                    $row[] = "<a $text_right href='" . url($val['display']['link'] . $link) . "' $attr>" .  tgl_indo($ls->$key, 'angka', true, true) . "</a>";
                                } else {
                                    $row[] = tgl_indo($ls->$key, 'no', true, true);
                                }
                            }
                            // #OPTION
                        } elseif ($val['display']['type'] == 'option') {
                            if (array_key_exists($ls->$key, $val['data'])) {
                                $row[] = $val['data'][$ls->$key];
                            } else {
                                $row[] = '';
                            }

                            // #DATE
                        } elseif ($val['display']['type'] == 'date') {
                            if ($ls->$key != '0000-00-00') {
                                $row[] = "<div $text_right>" . tgl_indo($ls->$key) . "</div>";
                            } else {
                                $row[] = '';
                            }
                            // #CURRENCY
                        } elseif ($val['display']['type'] == 'currency') {
                            $row[] = number_format($ls->$key, 0, '.', '.');

                            // CLOCK
                        } elseif ($val['display']['type'] == 'jam') {
                            $row[] = substr($ls->$key, 0, -3);

                            // COLOR
                        } elseif ($val['display']['type'] == 'color') {
                            $row[] = "<div style='background-color: " . $val['display']['color'] . "; padding: 10px; color: #000;'>" . htmlspecialchars($ls->$key) . "</div>";

                            // RUPIAH
                        } elseif ($val['display']['type'] == 'rupiah') {
                            $row[] = nominal_rupiah($ls->$key);
                        } elseif ($val['display']['type'] == 'link_rupiah') {
                            $row[] = "<a $text_right href='" . url($val['display']['link'] . $link) . "' $attr>" .  nominal_rupiah($ls->$key) . "</a>";
                        } else {
                            $row[] = "<div $text_right $attr>" .  $ls->$key . "</div>";
                        }
                    }
                } else {
                    if ($val['type'] == 'option') {
                        $row[] = $val['data'][$ls->$key];
                    } elseif (isset($val['sub-type']) && $val['sub-type'] == 'date') {
                        // dd($ls->$key != '0000-00-00');
                        if ($ls->$key != '0000-00-00') {
                            $row[] = tgl_indo($ls->$key) . 'xx';
                        } else {
                            $row[] = '';
                        }
                    } elseif ($val['type'] == 'like') {
                        if (in_array("specialchars", $val)) {
                            $row[] = $ls->$key;
                        } else {
                            $row[] = htmlspecialchars($ls->$key);
                        }
                    } else {
                        $row[] = htmlspecialchars($ls->$key);
                    }
                }
            }
        }
        $data[] = $row;
    }

    return $data;
}
function boks_get_list($table, $key, $val, $first = false, $where = null, $hashid = false, $where_auth = null, $orderBy = null)
{
    if (is_array($table)) {
        $data = DB::select($table['query']);
    } else {
        $data = DB::table($table);
        if ($where) {
            foreach ($where as $wk => $wv) {
                if (is_array($wv)) {
                    if (is_array($wv[1])) {
                        $data->whereIn($wk, $wv[1]);
                    } else {
                        $data->where($wk, $wv[0], $wv[1]);
                    }
                } else {
                    $data->where($wk, $wv);
                }
            }
        }

        if ($where_auth) {
            foreach ($where_auth as $wk => $wv) {
                if (is_array($wv)) {
                    $data->where($wk, $wv[0], auth()->user()->{$wv[1]});
                } else {
                    $data->where($wk, auth()->user()->{$wv});
                }
            }
        }

        if ($orderBy) {
            $data->orderBy($orderBy[0], $orderBy[1]);
        } else if (is_string($val)) {
            $data = $data->orderBy($val, 'asc');
        }
        $data = $data->get();
    }

    $output = [];
    if ($first == true) {
        $output[0] = '-';
    }
    foreach ($data as $d) {
        if ($hashid) {
            $output[$d->$key] = $d->$val;
        } else {
            if ($d->$key !== '' && ((is_array($val) && count($val) > 0) || (is_string($val) && $val != ''))) {
                if (is_array($val)) {
                    if ($val['wrap'] == '()') {
                        $val1 = $val['concat'][0];
                        $val2 = $val['concat'][1];
                        $value = $d->$val1 . ' (' . $d->$val2 . ')';
                    } elseif ($val['wrap'] == '-') {
                        $val1 = $val['concat'][0];
                        $val1 = $d->$val1;
                        if (array_key_exists('mode', $val) && $val['mode'] == 'date') {
                            $val1 = tgl_indo($val1, 'no', 1, false);
                        } else if (array_key_exists('mode', $val) && $val['mode'] == 'datetime') {
                            $val1 = tgl_indo($val1, 'no', 1, true);
                        }
                        $val2 = $val['concat'][1];
                        $val2 =  $d->$val2;
                        if (array_key_exists('mode', $val) && $val['mode'] == 'date') {
                            $val2 = tgl_indo($val2, 'no', 1, false);
                        } else if (array_key_exists('mode', $val) && $val['mode'] == 'datetime') {
                            $val2 = tgl_indo($val2, 'no', 1, true);
                        }
                        $value = $val1 . ' - ' . $val2;
                    }
                    $output[$d->$key] = $value;
                } else {
                    $output[$d->$key] = $d->$val;
                }
            } else {
                $output[$d->$key] = 'Tidak diketahui ' . $d->$key . ' & ' . $d->$val;
            }
        }
    }

    return $output;
}
function get_clean_array($array)
{
    $output = [];
    foreach ($array as $c => $k) {
        if (!is_array($k)) {
            if (cek_word($k, 'like')) {
                $nc = get_like_word($k);
                $output[] = $nc;
            } elseif (cek_word($k, 'from')) {
                $f = get_from_word($k);
                $output[] = $f[1];
            } elseif (cek_word($k, 'no_search')) {
                $nc = get_like_word($k);
                $output[] = $nc;
            }
        } else {
            $output[] = $c;
        }
    }

    $output;

    return $data[] = $output;
}

function cek_word($str, $word)
{
    $c = explode(" ", $str);
    if ($c[0] == $word) {
        return true;
    } else {
        return false;
    }
}

function get_from_word($str)
{
    $c = explode(" ", $str);
    return [$c[1], $c[2], $c[3], $c[4]];
}


function get_like_word($str)
{
    $c = explode(" ", $str);
    return $c[1];
}
