<?php
if(!function_exists('getStrBefore')) {
    function getStrBefore($sub, $str) {
        return substr($str, 0, strpos($str, $sub));
    }
}

if(!function_exists('arrayPaginator')) {
    function arrayPaginator($array, $request) {
    $page = Illuminate\Support\Facades\Input::get('page', 1);
    $perPage = 10;
    $offset = ($page * $perPage) - $perPage;

    return new Illuminate\Pagination\LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
        ['path' => $request->url(), 'query' => $request->query()]);
  }
}
?>