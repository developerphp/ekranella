<?php
/**
 * Created by PhpStorm.
 * User: coskudemirhan
 * Date: 24/12/14
 * Time: 21:52
 */

namespace admin;

class RoleController extends \BaseController
{
    public static function control($item)
    {
        $id = \Auth::user()->id;

        $user = \User::find($id);

        if ($user['role'] == 1)
            return true;

        else {
            $itemCreator = $item->created_by;

            if ($itemCreator == $id)
                return true;
            else
                return false;
        }
    }

    public static function isAdmin(){
        if( $id = \Auth::user()->role == 1)
            return true;
        else
            return false;
    }

    public static function getRoleAlias(){
        $user = \Auth::user();
        $roles = \Config::get('alias.roles');
        return $roles[$user->role];
    }

}