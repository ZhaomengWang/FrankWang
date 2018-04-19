<?php

namespace FileManger;

use Illuminate\Database\Eloquent\Model;

class FileModel extends Model
{
    const CREATED_AT = 'file_upload_time';
    protected $table = 'platform_files';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'file_user_id',
        'file_title',
        'file_massage',
        'coin',
        'file_loc',
        'file_type',
        'size',
        'first_tag',
        'tag',
        'tags',
        'file_download',
        'file_visit',
    ];
}
