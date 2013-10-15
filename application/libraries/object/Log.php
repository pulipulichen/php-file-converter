<?php
//include_once '../toolkit/Generic_object.php';
/**
 * Log
 *
 * log full description.
 *
 * @package		KALS
 * @category		Libraries
 * @author		Pudding Chen <puddingchen.35@gmail.com>
 * @copyright		Copyright (c) 2010, Pudding Chen
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://sites.google.com/site/puddingkals/
 * @version		1.0 2013/10/15 下午 01:45:13
 */
class Log extends Generic_object {

    // --------
    // Generic Object 設定
    // -------

    protected $table_name = 'log'; //資料表名稱
    protected $primary_key = 'log_id'; //資料表主鍵
    protected $table_fields = array('log_id', 'timestamp', 'action', 'ip', 'bitstream_id'); //資料表欄位

    //以下是選填資料，預設關閉
//    protected $not_null_field = array('_id', );    //非空值約束
//    protected $unique_restriction = array('_id');    //單一約束
//    protected $except_bind_fields = array();    //排除脫逸欄位
//    protected $default_field = '';  //預設欄位
//    protected $fake_delete = 'deleted';    //假性刪除欄位
//    protected $use_cache = FALSE;   //是否使用快取
//    protected function _post_construct($table_name = NULL, $id = NULL)  //__construct()完成後動作

    /**
     * 在Update前，新增使用者的IP資料
     * @param type $data
     */
    protected  function _pre_update($data)  //update()前動作
    {
        $data["ip"] = get_user_ip();
        return $data;
    }
//    protected function _post_update()   //update()後動作
//    protected  function _pre_insert($data)  //新增(insert)前動作
//    protected  function _post_insert()  //新增(insert)後動作
//    protected  function _pre_delete()   //delete()前動作
//    protected  function _pre_create($data)  //create()前動作

    // --------
    // log Member Variables
    // --------

    // --------
    // log Methods
    // --------

    /**
     * 設定Bitstream ID
     * @param Bitstream|Int $bitstream_id
     * @return Log
     */
    public function set_bitstream_id($bitstream_id) {
        if (is_object($bitstream_id)) {
            $bitstream_id = $bitstream_id->get_id();
        }
        $this->set_field('bitstream_id', $bitstream_id);
        return this;
    }
    
    /**
     * 設定Bitstream
     * @param Bitsream|Int $bitstream
     * @return Log
     */
    public function set_bitstream($bitstream) {
        return $this->set_bitstream_id($bitstream);
    }
    
    /**
     * 設定動作
     * @param String $action
     * @return Log
     */
    public function set_action($action) {
        return $this->set_field('action', $action);
    }
    
    /**
     * 建立記錄
     * @param Bitstream $bitstream
     * @param String $action
     * @return Log
     */
    static public function create_log($bitstream, $action) {
        $log = new Log();
        $log->set_bitstream($bitstream);
        $log->set_action($action);
        $log->update();
        return $this;
    }
}

/* End of file log.php */
/* Location: ./system/application/libraries/.../log.php */