<?php

namespace Spatie\JsonApiPaginate;

use \Illuminate\Pagination\Paginator;

class JeasyuiSimplePaginator extends Paginator
{
    
    /**
    * Get the instance as an array.
    *
    * @return array
    */
    public function toArray()
    {
        return [
        'current_page' => $this->currentPage(),
        'rows' => $this->items->toArray(),
        'first_page_url' => $this->url(1),
        'from' => $this->firstItem(),
        'next_page_url' => $this->nextPageUrl(),
        'path' => $this->path,
        'per_page' => $this->perPage(),
        'prev_page_url' => $this->previousPageUrl(),
        'to' => $this->lastItem(),
        ];
    }
}