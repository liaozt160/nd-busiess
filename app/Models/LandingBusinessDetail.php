<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingBusinessDetail extends Model
{
    protected $fillable = [
        'landing_business_id', 'business_id'
    ];
    protected $guarded = ['id'];
    protected $table = 'landing_business_detail';
}
