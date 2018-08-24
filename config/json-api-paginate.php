<?php

return [

    /*
     * The maximum number of results that will be returned
     * when using the JSON API paginator.
     */
    'max_result' => 50,

    /*
     * The default number of results that will be returned
     * when using the JSON API paginator.
     */
    'default_size' => 10,

    /*
     * The key of the page[x] query string parameter for page number.
     */
    'page_parameter' => 'page',

    /*
     * The key of the page[x] query string parameter for page size.
     */
    'row_parameter' => 'rows',

    /*
     * The name of the macro that is added to the Eloquent query builder.
     */
    'method_name' => 'jsonPaginate',

    /*
     * Here you can override the base url to be used in the link items.
     */
    'base_url' => null,

    /*
     * run the query total in order to find the total number of rows
     */
    'show_total' => true,
];
