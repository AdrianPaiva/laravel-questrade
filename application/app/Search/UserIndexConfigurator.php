<?php

namespace App\Search;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class UserIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        "analysis" => [
        	"analyzer" => [
            	"my_analyzer" => [
        			"tokenizer" => "my_tokenizer"
        		]
            ],
        	"tokenizer" => [
        		"my_tokenizer" => [
        			"type" => "ngram",
        			"min_gram" => 4,
        			"max_gram" => 8,
        			"token_chars" => [
        				"letter",
        				"digit"
        			]
        		]
        	]
        ]
    ];
}
