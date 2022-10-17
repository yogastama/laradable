<?php

function tgl_dt($tgl)
{
    $cv = ['', '0000-00-00', '1970-01-01'];

    $arr_tgl = explode(" ", $tgl);

    if (!in_array($tgl, $cv)) {
        $kirim = date("Y-m-d", strtotime($tgl));
        if (isset($arr_tgl[1])) {
            $kirim .= date(" H:i:s", strtotime($tgl));
        }

        return $kirim;
    } else {
        return "";
    }
}
function tgl_indo($tgl, $bln = 'angka', $pj = 0, $time = true)
{
    $cv = ['', ' ', '0000-00-00', '1970-01-01'];
    $arr_tgl = explode(" ", $tgl);

    if (!in_array($tgl, $cv)) {
        if ($bln == 'angka') {
            $kirim = date("d-m-Y", strtotime($tgl));
        } else {
            $kirim = date("d ", strtotime($tgl));
            $kirim .= boks_bulan(date("m", strtotime($tgl)), $pj);
            $kirim .= date(" Y", strtotime($tgl));
        }
        if ($time) {
            if (isset($arr_tgl[1])) {
                $kirim .= date(" H:i:s", strtotime($tgl));
            }
        }

        return $kirim;
    } else {
        return "";
    }
}
function boks_bulan($gt, $pj = 0)
{

    if ($pj == 0) {
        $bln = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'Aug', '09' => 'Sept', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
    } else {
        $bln = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
    }

    if (array_key_exists($gt, $bln)) {
        return $bln[$gt];
    } else {
        return "Error: boks_bulan() at first parameter";
    }
}
function nominal_rupiah($nominalRupiah, $rp = 1, $separator = ',')
{
    $nominalRupiah = number_format($nominalRupiah, 0, '.', $separator);

    if ($rp == 1) {
        $nominalRupiah = "Rp$nominalRupiah";
    }

    return $nominalRupiah;
}
function boks_input($data)
{
    /**
     * name
     * ? type
     * ? value
     * ? attr
     * ? class
     */

    $_name  = '';
    $_type  = 'text';
    $_value = '';
    $_class = '';
    $_attr  = '';

    if (array_key_exists("name", $data)) {
        $_name = $data['name'];
    }
    if (array_key_exists("type", $data)) {
        $_type = $data['type'];
    }
    if (array_key_exists("value", $data)) {
        $_value = $data['value'];
    }
    if (array_key_exists("class", $data)) {
        $_class = $data['class'];
    }

    if (array_key_exists("attr", $data)) {
        foreach ($data["attr"] as $key => $val) {
            $_attr .= $key . '=' . '"' . $val . '"';
        }
    }

    $output = "<input name='$_name' type='$_type' class='$_class' value='$_value' $_attr>";
    return $output;
}


function boks_select($data)
{
    /**
     * name
     * options
     * ? value
     * ? attr
     * ? class
     * ? first
     */

    $_value = '';
    $_class = '';
    $_attr  = '';

    if (array_key_exists("value", $data)) {
        $_value = $data['value'];
    }

    if (array_key_exists("class", $data)) {
        $_class = $data['class'];
    }

    if (array_key_exists("attr", $data)) {
        foreach ($data["attr"] as $key => $val) {
            $_attr .= $key . '=' . '"' . $val . '"';
        }

        if (array_key_exists("required", $data['attr'])) {
            $_class .= ' required';
        }
    }

    $output = "<select id='" . $data['name'] . "' name='" . $data['name'] . "' class='$_class' $_attr>";

    if (array_key_exists("first", $data)) {
        foreach ($data['first'] as $key => $val) {
            $_selected = '';
            if ($_value != '') {
                if ($_value == $key) {
                    $_selected = ' selected';
                }
            }

            $output .= '<option value="' . $key . '"' . $_selected . '>' . $val . '</option>';
        }
    }

    foreach ($data['options'] as $opt_key => $val) {
        $_selected = '';
        if ($_value != '') {
            if ($_value == $opt_key) {
                $_selected = ' selected';
            }
        }

        $output .= '<option value="' . $opt_key . '"' . $_selected . '>' . $val . '</option>';
    }
    $output .= '</select>';

    return $output;
}
