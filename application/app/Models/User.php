<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function QuestradeCredential()
    {
        return $this->hasOne('App\Models\QuestradeCredential');
    }
     
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
