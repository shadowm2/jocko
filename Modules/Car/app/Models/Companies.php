<?php

namespace Modules\Car\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Car\Database\Factories\CompaniesFactory;

class Companies extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): CompaniesFactory
    // {
    //     // return CompaniesFactory::new();
    // }
}
