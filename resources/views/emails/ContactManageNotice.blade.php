<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: win
 * Date: 2018/7/12
 * Time: 14:08
 */
?>

<div>
    <div>
        <span>Name:</span><span>{{$contact->name}}</span>
    </div>
    <div>
        <span>Phone:</span><span>{{$contact->phone}}</span>
    </div>
    <div>
        <span>Email:</span><span>{{$contact->email}}</span>
    </div>
    <div>
        <span>Role:</span><span>{{getRoles($contact->role)}}</span>
    </div>
    <div>
        <span>Remark:</span><span>{{$contact->remark}}</span>
    </div>
    <div>
        <span>Time:</span><span>{{$contact->created_at}}</span>
    </div>
</div>