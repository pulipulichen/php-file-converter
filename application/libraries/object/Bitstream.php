<?php
//include_once '';
/**
 * Bitstream
 *
 * Bitstream full description.
 *
 * @package		KALS
 * @category		Libraries
 * @author		Pudding Chen <puddingchen.35@gmail.com>
 * @copyright		Copyright (c) 2010, Pudding Chen
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://sites.google.com/site/puddingkals/
 * @version		1.0 2013/10/15 下午 02:54:20
 */
class Bitstream extends Generic_object {

    // --------
    // Generic Object 設定
    // -------

    protected $table_name = 'bitstream'; //資料表名稱
    protected $primary_key = 'bitstream_id'; //資料表主鍵
    protected $table_fields = array('bitstream_id'
        , 'original_name'
        , 'size'
        , 'mime'
        , 'internal_name'
        , 'deleted'
        , 'type'
        , 'original_id'
    ); //資料表欄位

    //以下是選填資料，預設關閉
//    protected $not_null_field = array('_id');    //非空值約束
//    protected $unique_restriction = array('_id');    //單一約束
//    protected $except_bind_fields = array();    //排除脫逸欄位
//    protected $default_field = '';  //預設欄位
    protected $fake_delete = 'deleted';    //假性刪除欄位
//    protected $use_cache = FALSE;   //是否使用快取

    protected function _post_construct($table_name = NULL, $id = NULL)  //__construct()完成後動作
    {
        $this->CI->load->helper("file");
    }
//    protected  function _set_field_filter($cond)    //set_field()資料過濾
//    protected  function _get_field_filter($field)   //get_field()資料過濾
//    protected  function _pre_update($data)  //update()前動作
//    protected function _post_update()   //update()後動作
    /**
     * 新增資料前，設定資料的size跟mime
     * @param type $data
     */
    protected  function _pre_insert($data)  //新增(insert)前動作
    {
        $file_ready = (isset($data["internal_name"]) && isset($data["type"]));
        if (isset($data['size']) === FALSE && $file_ready) {
            $data['size'] = $this->get_size();
        }
        if (isset($data['mime']) === FALSE && $file_ready) {
            $data['mime'] = $this->get_mime();
        }
        return $data;
    }
//    protected  function _post_insert()  //新增(insert)後動作
//    protected  function _pre_delete()   //delete()前動作
//    protected  function _pre_create($data)  //create()前動作

    // --------
    // Bitstream Member Variables
    // --------

    // --------
    // Bitstream Methods
    // --------

    /**
     * 取得檔案大小
     * @type int 檔案大小
     */
    public function get_size() {
        $size = $this->get_field('size');
        
        if (is_null($size)) {
            $size = filesize($this->get_path());
            $this->set_field("size", $size);
        }
        
        return $size;
    }
    
    /**
     * 取得檔案MIME型態
     * @type string 檔案MIME型態
     */
    public function get_mime() {
        $mime = $this->get_field('mime');
        
        if (is_null($mime)) {
            $mime = get_mime_by_extension($this->get_path());
            $this->set_field("mime", $mime);
        }
        
        return $mime;
    }
    
    /**
     * 取得檔案路徑
     * @return string 檔案路徑
     */
    public function get_path() {
        $internal_name = $this->get_field('internal_name');
        $type = $this->get_field("type");

        $dir_path = $this->CI->config->item("upload_path");
        if ($type == "converted") {
            $dir_path = $this->CI->config->item("convert_files", "completed");
        }
        $replace_separator = "/";
        if ($replace_separator == DIRECTORY_SEPARATOR) {
            $replace_separator = "\\";
        }
        $dir_path = str_replace($replace_separator, DIRECTORY_SEPARATOR, $dir_path);
        
        if (substr($dir_path, -1) != DIRECTORY_SEPARATOR) {
            $dir_path = $dir_path . DIRECTORY_SEPARATOR;
        }
        
        $needle = 'application';
        $base_path = substr(__DIR__, 0, strpos(__DIR__, $needle));
        
        $full_path = $base_path . $dir_path . $internal_name;
        
        return $full_path;
    }
    
    /**
     * 刪除時，同時刪除檔案系統中的檔案
     */
    public function delete() {
        $path = $this->get_path();
        unlink($path);
        
        if ($this->config->item("reserve_original") === TRUE
                && $this->is_original() === FALSE) {
            $original_bitstream = $this->get_original_bitstream();
            $original_bitstream->delete();
        }
        parent::delete();
    }
    
    /**
     * 取得原始檔案
     * @return Bitstream 原始檔案
     */
    public function get_original_bitstream() {
        if ($this->is_original()) {
            return $this;
        }
        else {
            $original_id = $this->get_field("original_id");
            return new Bitstream($original_id);
        }
    }


    /**
     * 取得原始檔案的路徑
     * @return string 原始檔案的路徑
     */
    public function get_original_path() {
        if ($this->is_original()) {
            return $this->get_path();
        }
        else {
            $original_bitstream = $this->get_original_bitstream();
            return $original_bitstream->get_path();
        }
    }
    
    /**
     * 是否是原始檔案
     * @return boolean 是否是原始檔案
     */
    public function is_original() {
        return ($this->get_type() == "orginal");
    }
    
    /**
     * 取得檔案類型
     * @example "orginal" 原始檔案
     * @example "copy" copy模組轉換過的檔案
     * @return String 檔案的類型
     */
    public function get_type() {
        return $this->get_field('type', 'original');
    }
    
    /**
     * 取得檔案名稱
     * @return string
     */
    public function get_original_name() {
        return $this->get_field("original_name");
    }
    
    /**
     * 轉換過的檔案
     * @var Bitstream 轉換過的檔案
     */
    private $converted_bitstream = NULL;
    
    /**
     * 取得轉換過的檔案
     * @return Bitstream 轉換過的檔案
     */
    public function get_converted_bitstream() {
        if (is_null($this->get_converted_bitstream())) {
            $db = $this->CI->db;
        
            $db->select($this->primary_key);
            $db->from($this->table_name);
            $db->where("original_id", $this->get_id());
            
            $query = $db->get();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                $this->converted_bitstream = new Bitstream($result[$this->primary_key]);
            }
        }
        return $this->converted_bitstream;
    }
    
    /**
     * 轉換是否完成
     * @return Boolean
     */
    public function is_convert_completed() {
        return (is_null($this->get_converted_bitstream()));
    }
    
    /**
     * 是否是轉換過的檔案
     * @return Boolean
     */
    public function is_converted() {
        return (!$this->is_original());
    }
}

/* End of file Bitstream.php */
/* Location: ./system/application/libraries/.../Bitstream.php */