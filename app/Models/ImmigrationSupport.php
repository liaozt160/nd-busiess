<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImmigrationSupport extends Model
{
    protected $fillable = ['business_id', 'immigration_support_tag_id','created_at'];

    public function business(){
        return $this->belongsTo('App\Models\Business', 'business_id');
    }

    public function businessZh(){
        return $this->belongsTo('App\Models\BusinessZh', 'business_id','business_id');
    }

    public static function getTagsByIds($ids=[]){
        $tags = self::select(['business_id','immigration_support_tag_id as tags'])
            ->whereIn('business_id',$ids)->get();
        $tags = self::mapToGroup($tags);
        return $tags;
    }

    public static function getTagsByTags($tags=[]){
        $tags = self::select(['business_id','immigration_support_tag_id as tags'])
            ->whereIn('immigration_support_tag_id',$tags)->get();
        return $tags;
    }

    public static function mapToGroup($tags){
        $tags = $tags->mapToGroups(function ($item, $key) {
            return [$item['business_id'] => $item['tags']];
        });
        return $tags;
    }

}
