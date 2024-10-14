<?php
return [

    'driver' => env('SCOUT_DRIVER', 'tntsearch'),

    // Other drivers (Algolia, MeiliSearch, etc.) configurations go here

    'tntsearch' => [
        'storage'  => storage_path('search'), // Path to where the indexes will be stored
        'fuzziness' => env('TNTSEARCH_FUZZINESS', false), // Enable or disable fuzzy search
        'asYouType' => false, // Whether to search as you type
        'searchBoolean' => env('TNTSEARCH_BOOLEAN', false), // Use boolean search
    ],

];
