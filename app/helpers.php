<?php

if(!function_exists('getMonth()')) {
    function getMonth(int $index, bool $indexed = false) {
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return ($indexed) ? $months[$index] : $months[$index - 1];
    }
}
