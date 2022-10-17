<?php

namespace Yogastama\Laradable;

use Illuminate\Http\Request;

class DataTableControllerTrait
{

    public function table(Request $request)
    {
        $list = dt_get($this->_table, $this->_query ?? []);
        $output = array(
            "draw"            => $request->draw,
            "recordsTotal"    => dt_count_all($this->_table, $this->_query ?? []),
            "recordsFiltered" => dt_count_filtered($this->_table, $this->_query ?? []),
            "data"            => dja_row($this->_col, $list),
        );

        return response()->json($output);
    }
}
