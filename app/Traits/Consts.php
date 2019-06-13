<?php
/**
 * Created by PhpStorm.
 * Author: Tank
 * User: win
 * Date: 2018/6/13
 * Time: 15:37
 */

namespace App\Traits;


class Consts {

    /**
     * System info
     */
    const SUCCESS = 1001;
    const UNKNOWN_ERROR = 1002;
    const DATABASE_ERROR = 1003;
    const DATA_VALIDATE_WRONG = 1005;
    const SAVE_RECORD_FAILED = 1006;
    const NO_RECORD_FOUND = 1007;

    /**
     * Account info
     */

    const ACCOUNT_ACCESS_LEVEL_ONE = 1;
    const ACCOUNT_ACCESS_LEVEL_TWO = 2;
    const ACCOUNT_ACCESS_LEVEL_THREE = 3;

    const ACCOUNT_ROLE_ADMIN = 1;
    const ACCOUNT_ROLE_BUYER_BROKER = 2;
    const ACCOUNT_ROLE_BUSINESS_BROKER = 3;
    const ACCOUNT_ROLE_USER = 4;

    const ACCOUNT_SAVE_ERROR = 2001;
    const ACCOUNT_LOGIN_FAILED = 2002;
    const ACCOUNT_ACCESS_DENY = 2003;
    const ACCOUNT_EXIST = 2004;

    const TOKEN_WRONG = 2100;
    const TOKEN_NOT_PROVIDED = 2101;
    const TOKEN_USER_NOT_FOUND = 2102;


}