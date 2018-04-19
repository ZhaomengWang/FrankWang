<?php
/**
 * Created by PhpStorm.
 * User: 王兆盟
 * Date: 2018/4/19
 * Time: 17:37
 */
namespace FileManger;
use Illuminate\Database\Eloquent\Model;
class File extends Model
{
    protected $id;
    protected $name;
    protected $tempName;
    protected $path;
    protected $extension;
    protected $type;
    public $allowExtension = [
        "image" => [
            "png",
            "gif",
            "jpg"
        ],
        "document" => [
            "xls",
            "xlsx",
            "doc",
            "docx",
            "ppt",
            "pptx",
            "pdf",
            "txt"
        ],
        "package" => [
            "zip",
            "rar"
        ]
    ];
    protected $size;
    static public $sizeLimit = [
        "image" => 1024 * 1024 * 2,
        "document" => 1024 * 1024 * 4,
        "package" => 1024 * 1024 * 1024
    ];
    public $message;

    public function uploadInit()
    {
        $this->name = $_FILES['file']["name"];
        $this->size = $_FILES["file"]["size"];
        $this->type = $_FILES["file"]["type"];
        $this->tempName = $_FILES["file"]["tmp_name"];
        $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
        $this->message = $_FILES["file"]["error"];
        return $this->message;
    }

    //设置上传路径
    public function setUploadPath($path)
    {
        $this->path = $path;
    }

    //将文件上传至服务器
    public function upload($path)
    {
        $this->setUploadPath($path);
        if (!file_exists($this->path))
            mkdir($this->path);
        if ($this->uploadInit())
            return '上传失败';
        else if ($this->checkFile() == 1)
            return '文件过大';
        else if ($this->checkFile() == 2)
            return '格式错误';
        else if ($this->checkFile() == 0)
            if ($this->checkSame())
                return '上传地址存在同名文件';
            else {
                //修改文件数据库
                $file = new FileModel();
                $file->file_title = $this->name;
                $file->file_loc = $this->path;
                $file->size = $this->size;
                $file->file_type = $this->type;
                $file->file_download = 0;
                $file->save();
                move_uploaded_file($this->tempName, $this->path . $this->name);//保存文件至设定目录
                return '上传成功';
            }
    }

    //检查是否有同名文件
    public function checkSame()
    {
        if (file_exists($this->path . $this->name))
            return 1;
        else
            return 0;
    }

    //检查文件格式及大小
    public function checkFile()
    {
        if (in_array($this->extension, $this->allowExtension["image"]))
            if ($this->size < self::$sizeLimit["image"])
                return 0;
            else {
                return 1;
            }
        else if (in_array($this->extension, $this->allowExtension["document"]))
            if ($this->size < self::$sizeLimit["document"])
                return 0;
            else {
                return 1;
            }
        else if (in_array($this->extension, $this->allowExtension["package"]))
            if ($this->size < self::$sizeLimit["package"])
                return 0;
            else {
                return 1;
            }
        else {
            return 2;
        }
    }

    //将文件从服务器下载至本地
    public function download($id,$visitor_id)
    {
        $file = new FileModel();
        $file = $file->find($id);
        $this->path = $file['file_loc'];
        $this->name = $file['file_title'];
        //数据库操作部分
        $file->update([
            'downloadAmount' => $file->fillable['file_download'] + 1
        ]);
        $download = new DownlaodModel();
        $download->id_visitor = $visitor_id;
        $download->id_file = $file->id;
        $download->save();
        //下载部分
        $file = fopen($this->path . $this->name, "r");
        header("Content-Type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($this->path . $this->name));
        header("Content-Disposition: attachment; filename=" . $this->name);
        echo fread($file, filesize($this->path . $this->name));
        fclose($file);

    }
}