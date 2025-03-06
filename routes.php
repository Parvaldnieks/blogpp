<?php

return [
    "GET" => [
        "/" => "BlogController@index",         // Show all posts
        "/post/create" => "BlogController@create", // Show post creation form
        "/post/{id}" => "BlogController@show",     // Show a single post
        "/post/{id}/edit" => "BlogController@edit" // Show edit form
    ],
    "POST" => [
        "/post" => "BlogController@store",  // Handle post creation
    ],
    "PATCH" => [
        "/post/{id}" => "BlogController@update" // Handle post updates
    ],
    "DELETE" => [
        "/post/{id}" => "BlogController@destroy" // Handle post deletion
    ]
];