<?php

namespace App\Models;

use App\Traits\CreatesUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;
    
    /**
     * @var array
     */
    protected $guarded = [
    
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // protected $searchable = ['first_name', 'last_name', 'email'];

    // protected $indexConfigurator = \App\Search\UserIndexConfigurator::class;

    // protected $searchRules = [
    //     //
    // ];

    // protected $mapping = [
    //     'properties' => [
    //         "description"  =>  [ 
    //             "type" => "text",  
    //         ],
    //         "domain_id"  =>  [ 
    //             "type" => "long",
    //         ],
    //         "name" => [
    //             "type" => "text",
    //             "fields" => [
    //                 "ngram" => [
    //                     "type" => "text",
    //                     "analyzer" => "my_analyzer"
    //                 ],
    //             ],
    //         ],
    //     ]
    // ];

    /**
     * Fields that are searchable
     */
    // public function toSearchableArray()
    // {
    //     return $this->only('first_name', 'last_name', 'email');
    // }
    //
     
    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
