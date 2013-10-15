<?php
/**
 * Converter
 *
 * 轉換
 *
 * @package		KALS
 * @category		Libraries
 * @author		Pudding Chen <puddingchen.35@gmail.com>
 * @copyright		Copyright (c) 2010, Pudding Chen
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://sites.google.com/site/puddingkals/
 * @version		1.0 2013/10/15 下午 09:23:36
 */
class Converter extends KALS_object {
    
    /**
     * @var CI_DB_driver 
     */
    private $db;
    
    /**
     * @var Log
     */
    private $log;
    
    public function __construct() {
        parent::__construct();
        $this->db = $this->CI->db;
        
        $this->CI->load_library("object/log");
        $this->log = $this->CI->log;
        
        $this->CI->load_library("object/bitstream");
    }

    public function start() {
        $bitstream = $this->get_original_bitstream();
        
        if (is_null($bitstream)) {
            // 完成轉換，停止
            return $this;
        }
        
        $this->convert_start($bitstream);
        
        $this->convert_completed($bitstream);
        
        sleep($this->CI->config->item("wait_reload_interval"));
        $this->start();
    }
    
    /**
     * @return Bitstream
     */
    public function get_original_bitstream() {
        $sql = "select a.bitstream_id as orgianl_id, count(b.bitstream_id) as counverted_count "
            . "from bitstream as a left join bitstream as b on a.bitstream_id = b.original_id "
            . "group by a.bitstream_id "
            . "where converted_count < " . count($this->CI->config->item("converter"))." "
            . "order by a.bitstream_id asc limit 0,1";
        $query = $this->db->query($sql);
        
        if ($query->num_rows() == 0) {
            return NULL;
        }
        
        $result = $query->result_array();
        $bitstream_id = $result["original_id"];
        $bitstream = new Bitstream($bitstream_id);
        return $bitstream;
    }
    
    /**
     * 開始進行轉換
     * @param Bitstream $bitstream
     */
    public function convert_start($bitstream) {
        $this->log->create_log($bitstream, 'convert_start');
        
        // 開始進行轉換的手續
        
        $input_file = $bitstream->get_path();
        $converters = $this->CI->config->item("converter");
        
        foreach ($converters as $converter) {
            
            $converter_name = $converter["name"];
            $internal_name = $bitstream->get_internal_name();
            $output_file = $this->get_completed_dir() . $internal_name;

            $scrtips = $converter["script"];
            foreach ($scrtips as $step) {
                // 取代$step的資料
                $step = str_replace("[INPUT_FILE]", $input_file, $step);
                $step = str_replace("[OUTPUT_FILE]", $output_file, $step);
                
                exec($step);
            }
            
            //轉換完成，取得資料
            if (is_file($output_file)) {
                $converted_bitstream = new Bitstream();
                $converted_bitstream->set_field("internal_name", $internal_name);
                
                $converted_bitstream->set_field("original_name", $bitstream->get_original_name());
                $converted_bitstream->set_field("internal_name", $internal_name);
                $converted_bitstream->set_field("type", $converter_name);
                $converted_bitstream->set_field("original_id", $bitstream->get_id());
                
                $converted_bitstream->save();
                
                $this->log->create_log($converted_bitstream, $converter_name."_completed");
            }
            else {
                $this->log->create_log($bitstream, $converter_name."_error");
            }
        }
        
        return $this;
    }
    
    /**
     * 取得完成下載的路徑
     * @return string
     */
    public function get_completed_dir() {
        $dir_path = $this->CI->config->item("convert_files", "completed");
        
        $replace_separator = "/";
        if ($replace_separator == DIRECTORY_SEPARATOR) {
            $replace_separator = "\\";
        }
        $dir_path = str_replace($replace_separator, DIRECTORY_SEPARATOR, $dir_path);
        
        if (substr($dir_path, -1) != DIRECTORY_SEPARATOR) {
            $dir_path = $dir_path . DIRECTORY_SEPARATOR;
        }
        
        $base_path = $this->get_base_path();
        
        $full_path = $base_path . $dir_path;
        
        return $full_path;
    }
    
    /**
     * 取得本應用程式所在的實體路徑
     * @return String 路徑
     */
    public function get_base_path() {
        $needle = 'application';
        $base_path = substr(__DIR__, 0, strpos(__DIR__, $needle));
        return $base_path;
    }

    /**
     * 轉換完成
     * @param Bitstream $bitstream
     */
    public function convert_completed($bitstream) {
        $this->log->create_log($bitstream, 'convert_completed');
        return $this;
    }
}

/* End of file Converter.php */
/* Location: ./system/application/libraries/.../Converter.php */