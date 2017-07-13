<?php

namespace Acacha\Users\Traits;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class HasPaginator.
 * 
 * @package Acacha\Users\Traits
 */
trait HasPaginator
{
    /**
     * Create a length aware custom paginator instance.
     *
     * @param  Collection  $items
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginate($items, $perPage = 15)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentPageItems = collect($items)->slice(($currentPage - 1) * $perPage, $perPage);

        return new LengthAwarePaginator($currentPageItems, count($items), $perPage);
    }
}