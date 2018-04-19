<?php

namespace FileManger;


use Illuminate\Database\Eloquent\Model;

class DownloadModel extends Model
{
    protected $table = 'platform_download';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_file',
        'id_visitor',
    ];

}
