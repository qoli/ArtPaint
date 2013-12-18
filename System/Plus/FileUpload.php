<?php

// Qoli Wong _@2010

class FileUpload {

    private $Upload_FieldName;
    private $UploadSet_Filter;
    private $UploadSet_SavePath;
    private $UploadSet_SaveName;
    private $UploadSet_SaveExt;
    private $Upload_URL;
    private $Upload_FilePath;
    private $error;

    function __construct($_FieldName, $Filter, $FilePath, $FileName, $Size = 0) {

        //修正 FilePath 格式
        if ($FilePath[0] == '/') {
            $FilePath = substr($FilePath, 1);
        }
        if (substr($FilePath, -1) != '/') {
            $FilePath = $FilePath . '/';
        }

        $this->Upload_FieldName = $_FieldName;
        $this->UploadSet_Filter = $Filter;
        $this->UploadSet_SaveName = $FileName;
        $this->UploadSet_SavePath = $FilePath;
        $this->error = '';
        $this->UploadSet_SaveExt = $this->get_ext($_FILES[$this->Upload_FieldName]['name']);
        $this->Upload_URL = _URL . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '.' . $this->UploadSet_SaveExt;
        $this->Upload_FilePath = _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '.' . $this->UploadSet_SaveExt;
    }

    /**
     * 获得错误信息
     * @return string
     */
    function GetError() {
        return $this->error;
    }

    function GetURL() {
        return $this->Upload_URL;
    }

    function GetFilePath() {
        return $this->Upload_FilePath;
    }

    function Upload() {

        //建立路径
        if (!file_exists(_ROOT . $this->UploadSet_SavePath)) {
            d_mkdir(_ROOT . $this->UploadSet_SavePath);
        }

        //TODO 文件类型过滤
        if (strpos($this->UploadSet_Filter, $this->UploadSet_SaveExt)) {
            if ($this->uploadFile($this->UploadSet_SaveName . '.' . $this->UploadSet_SaveExt)) {
                $this->Upload_URL = _URL . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '.' . $this->UploadSet_SaveExt;
                $this->Upload_FilePath = _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '.' . $this->UploadSet_SaveExt;
                return TRUE;
            } else {
                $this->set_error('File upload failed');
                return FALSE;
            }
        } else {
            $this->set_error('File types unallowed');
            return FALSE;
        }
    }

    /**
     * 另存为 PNG
     * @return bool
     */
    function image_SaveASPNG() {
        if ($this->UploadSet_SaveExt == 'jpg') {
            $p = imagecreatefromjpeg($this->Upload_FilePath);
        }
        if ($this->UploadSet_SavePath == 'png') {
            $p = imagecreatefrompng($this->Upload_FilePath);
        }
        $ok = imagepng($p, _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '_saveas.png');
        if ($ok) {
            $this->Upload_URL = _URL . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '_saveas.png';
            $this->Upload_FilePath = _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '_saveas.png';
        }
        return $ok;
    }

    /**
     * 图片大小调整
     * @param int $w 宽度
     * @param int $h 高度
     */
    function image_resize($w, $h) {
        $ok = image_resize(
                _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '.' . $this->UploadSet_SaveExt, _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '_resize.' . $this->UploadSet_SaveExt, $w, $h);

        if ($ok) {
            $this->Upload_URL = _URL . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '_resize.' . $this->UploadSet_SaveExt;
            $this->Upload_FilePath = _ROOT . $this->UploadSet_SavePath . $this->UploadSet_SaveName . '_resize.' . $this->UploadSet_SaveExt;
        }
    }

    protected function uploadFile($name) {
        try {
            $ok = move_uploaded_file($_FILES[$this->Upload_FieldName]['tmp_name'], _ROOT . $this->UploadSet_SavePath . $name);
            if ($ok == FALSE) {
                throw new ExceptionEx('FileUpload');
            }
        } catch (ExceptionEx $exc) {
            dump($_FILES[$this->Upload_FieldName], '$_File');
            dump($this->UploadSet_SavePath . $name, 'SaveTo');
            echo $exc->Msg('FileUpload Error:

        

        ');
        }
    }

    protected function get_ext($file_name) {
        $extend = pathinfo($file_name);
        $extend = strtolower($extend["extension"]);
        return $extend;
    }

    protected function set_error($error) {
        $this->error = $error;
    }

}

?>
