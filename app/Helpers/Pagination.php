<?php

namespace App\Helpers;

class Pagination
{
    public static function generateNumber($page, $page_length)
    {
        $start = $page == 1 ? 1 : ($page * $page_length - $page_length) + 1;
        $end = $page == 1 ? $page_length : ($start + $page_length) - 1;
        $iteration = $page == 1 ? 1 : $start;

        return [
            'page' => $page,
            'page_length' => $page_length,
            'start' => $start,
            'end' => $end,
            'iteration' => $iteration,
        ];
    }
}
