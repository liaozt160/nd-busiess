<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class RecommendToBuyerBrokerDetail extends Model
{
    protected $fillable = [
        'recommend_id', 'business_id'
    ];
    protected $guarded = ['id'];
    protected $table = 'recommend_to_buyer_broker_detail';



}
