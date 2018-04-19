<?php
/**
 * Created by PhpStorm.
 * User: 王兆盟
 * Date: 2018/4/16
 * Time: 23:59
 */
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\DB;
class SearchModelModel extends Model
{

    public static function searchBytTitle($title)
    {
        $results = DB::select('select * from platform_files where find_in_set(?, file_title)', ['?'=> $title]);
        return $results;
    }
    public static function searchFilesByUser($user)
    {
        $results = DB::select('select * from platform_files where user_id = ?', ['?'=> $user]);
        return $results;
    }
    public static function searchFilesByTime($begin,$end)
    {
        $results = DB::select('select * from platform_files where file_upload_time Between "?" and "?"', [$begin,$end]);
        return $results;
    }
    public static function searchUser($user)
    {
        $results = DB::select('select * from platform_files where name = ?', ['?'=> $user]);
        return $results;
    }
    public static function searchUserByID($userID)
    {
        $results = DB::select('select * from platform_files where twt_id = ?', ['?'=> $userID]);
        return $results;
    }

}
