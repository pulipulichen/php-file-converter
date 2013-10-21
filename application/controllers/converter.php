<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Converter
 * 
 * 
 */
class Converter extends CI_Controller {

        /**
         * @var Puli_log
         */
        //private $puli_log = NULL;
    
        /**
         * 預先載入一些函式庫
         */
        public function __construct($bitstream_id = NULL) {
            parent::__construct();
            
            $this->load->library("object/Puli_log");
            //$this->puli_log = new Puli_log();
            
            $this->load->library("object/bitstream");
            
            if (is_null($bitstream_id) === FALSE) {
                $this->index($bitstream_id);
            }
        }

        /**
         * 預設首頁，會指到upload
         */
        public function index($bitstream_id = NULL) {
            if (is_null($bitstream_id)) {
                $this->do_upload();
            }
            else {
                $this->wait($bitstream_id);
            }
        }
    
        /**
         * 處理上傳檔案
         */
        public function do_upload() {
            $config['upload_path'] = $this->config->item("upload_path");

            $config['allowed_types'] = $this->config->item("allowed_types");
            $config['max_size'] = $this->config->item("max_size") * 1024;
            $config["max_filename"] = 0;
            $config['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config);
            
            
            if ($this->upload->do_upload('bitstream') === FALSE) {
                $message = $this->upload->display_errors("<p>", "</p>");
                $this->_message($message);
                return false;
            }
            
            $upload_data = $this->upload->data();
            //print_r($upload_data);
            
            //檢查內部是否有上傳成功
            if (!is_file($upload_data["full_path"])) {
                //$this->_message("upload failed");
                $upload_data = $this->_do_php_upload($upload_data);
                //return;
            }
            
            $internal_name = $upload_data['file_name'];
            $original_name = $upload_data['orig_name'];
            
            $bitstream = new Bitstream();
            $bitstream->set_field("original_name", $original_name);
            $bitstream->set_field("internal_name", $internal_name);
            $bitstream->set_field("type", "uploaded");
            
            // 設定parameters
            $i = 0;
            while (isset($_POST["params_".$i])) {
                $key = "params_".$i;
                $value = $_POST[$key];
                $bitstream->set_parameters($i, $value);
                $i++;
            }
            
            $bitstream->update();
            
            $bitstream_id = $bitstream->get_id();
            //echo "[".$bitstream_id."]";
            
            $this->puli_log->create_log($bitstream, "upload");
            
            // 觸動轉檔工作
            //$this->_start_convert_cli();
            
            // 記錄完畢，header去wait
            return $this->wait($bitstream_id);
        }
        
        private function _do_php_upload($upload_data) {
            $field_name = "bitstream";
            $tmp_path = $_FILES[$field_name]["tmp_name"];
            
            $target_path = $this->_create_random_filename();
            while (is_file($target_path)) {
                $target_path = $this->_create_random_filename();
            }
            move_uploaded_file($tmp_path, $target_path);
            
            $upload_data['file_name'] = substr($target_path, strrpos($target_path, DIRECTORY_SEPARATOR)+1);
            $upload_data["orig_name"] = $_FILES[$field_name]["name"];
            
            return $upload_data;
        }
        
        /**
         * 建立亂數檔案名稱
         * @return String
         */
        private function _create_random_filename() {
            $field_name = "bitstream";
            $upload_path = $this->config->item("upload_path");
            
            $filename = md5(uniqid(rand(), true));
            $origname = $_FILES[$field_name]["name"];
            $extname = substr($origname, strrpos($origname, ".")+1);
            
            $target_path = get_root_path($upload_path."/".$filename.".".$extname);
            return $target_path;
        }


        /**
         * 顯示轉換中的等待訊息
         * @param int $bitream_id
         */
	public function wait($bitstream_id) {
            
            $bitstream = new Bitstream($bitstream_id);
            
            //sleep(1);
            $is_convert_completed = $bitstream->is_convert_completed();
            //echo $is_convert_completed;
            if ($is_convert_completed === FALSE) {
                //還沒轉換完成喔
                
                $name = $bitstream->get_original_name();
                #$view_data["page_title"] = $this->lang->line("page_title");

                $view_data["message"] = $name . $this->lang->line("wait");
                $view_data["start_convert_uri"] = base_url('converter/start_convert');
                $view_data["wait_uri"] = base_url('converter/wait/'. $bitstream_id);
                $view_data["status_uri"] = base_url('converter/status/'. $bitstream_id);
                $view_data["download_uri"] = base_url('converter/download/');
                $view_data["deleted_uri"] = base_url('converter/deleted/'. $bitstream_id);
                $view_data["wait_reload_interval"] = $this->config->item("wait_reload_interval");

                $this->load->view('component/header', $view_data);
                $this->load->view('wait_view', $view_data);
                $this->load->view('component/footer');
            }
            else {
                //轉換完成哩
                
                $converted_bitstream = $bitstream->get_converted_bitstream();
                
                //$this->puli_log->create_log($bitstream, "delete");
                //$bitstream->delete();
                
                $this->download($converted_bitstream->get_id());
            }
	}
        
        /**
         * 處理狀態
         * @param {Int} $bitstream_id
         */
        public function status($bitstream_id) {
            $bitstream = new Bitstream($bitstream_id);
            $status = "wait";
            if ($bitstream->is_deleted()) {
                $status = "deleted";
            }
            else if ($bitstream->is_convert_completed()) {
                $converted_bitstream = $bitstream->get_converted_bitstream();
                $status = $converted_bitstream->get_id();
            }
            
            $this->load->view("component/json", array(
                "message" => $status
            ));
        }


        /**
         * 啟動轉換器，供命令列CLI使用
         */
        public function start_convert() {
            $this->load->library("Convert_handler");
            
            $convert_handler = new Convert_handler();
            $result = $convert_handler->start();
            
            if (is_string($result)) {
                $this->_message($result);
            }
        }
        
        /**
         * 以命令列的方式啟動轉換器
         */
        private function _start_convert_cli() {
            $filepath = __FILE__;
            $needle = "application";
            $index_dir = substr($filepath, 0, strpos($filepath, $needle));
            $index_path = $index_dir . "index.php";
            
            $command = "php";
        }

        /**
         * 下載檔案
         * @param int $bitstream_id
         */
        public function download($bitstream_id) {
            
            $bitstream = new Bitstream($bitstream_id);
            
            // 如果bs已經被刪除，轉移到錯誤訊息
            if (!is_object($bitstream) || $bitstream->is_deleted()) {
                return $this->deleted($bitstream_id);
            }
            
            // 記錄檔案下載
            $this->puli_log->create_log($bitstream, "download");
            
            // 輸出檔案
            $filepath = $bitstream->get_path();

            //$type = "application/octet-stream";
            $type = $bitstream->get_mime();
            $name = $bitstream->get_original_name();
            $name = urldecode($name);

            $this->_download_contents($filepath, $type, $name);
        }
        
        /**
        * SAVR 10/20/06 : force file download over SSL for IE
        * BIP  09/17/07 : inserted and tested for ProjectPier 
        * Was:
        * function download_contents($content, $type, $name, $size, $force_download = false) {
        */
       private function _download_contents($content, $type, $name) {
          $chunksize = 1*(1024*1024); // how many bytes per chunk
          $buffer = '';
          $handle = fopen($content, 'rb');

          $size = filesize($content);
          //echo $size;
          $this->_download_headers($name, $type, $size);

          if ($handle === false) {
            return false;
          }
          while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            print $buffer;
            flush();
            ob_flush();
          }
          return fclose($handle);
      } // download_contents

        /**
        * function download_headers($type, $name, $size, $force_download = false)
        */
        private function _download_headers($name, $type, $size, $force_download = true) {
          if ($force_download) {
            /** SAVR 10/20/06
            * Was:
            * header("Cache-Control: public");
            */
            header("Cache-Control: public, must-revalidate");
            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE") == false) {
                  header("Pragma: hack");
            }
            else {
                header('Pragma: public');
            }
          } else {
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE") == false) {
                  header("Pragma: no-cache");
            }
            else {
                header('Pragma: public');
            }
          } // if
          header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
          header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
          header("Content-Type: $type");
          header("Content-Length: $size");
          // Prepare disposition
          $disposition = $force_download ? 'attachment' : 'inline';
          // http://www.ietf.org/rfc/rfc2183.txt
          $download_name = strtr($name, " ()<>@,;:\\/[]?=*%'\"", '--------------------');
          //$download_name = normalize($download_name);
          // Generate the server headers
          if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
              $download_name = iconv('utf-8', 'big5', $download_name);
              header('Pragma: public');
          }
          header("Content-Disposition: $disposition; filename=\"$download_name\"");
          //header("Content-Disposition: $disposition; filename=$download_name");
          header("Content-Transfer-Encoding: binary");
        }

        /**
         * 顯示已經被錯誤下載的訊息
         * @param int $bitstream_id
         */
        public function deleted() {
            $this->_message($this->lang->line("deleted"));
        }
        
        /**
         * 解鎖
         */
        public function unlock() {
            
            $this->load->library("Convert_handler");
            
            $convert_handler = new Convert_handler();
            $convert_handler->unlock();
            
            $this->load->view('component/header');
            $this->load->view('unlock_view');
            $this->load->view('component/footer');
        }
        
        /**
         * 重置資料
         */
        public function reset() {
            $debug = false;
            
            $this->load->library("Convert_handler");
            
            $convert_handler = new Convert_handler();
            $convert_handler->unlock();
            
            // 刪除指定目錄檔案
            $file_dirs = $this->config->item("convert_files");
            
            foreach ($file_dirs AS $file_dir) {
                $files = get_filenames($file_dir);
                foreach ($files AS $file) {
                    $path = get_root_path($file_dir.DIRECTORY_SEPARATOR.$file);
                    
                    if ($debug) {
                        echo "unlink: ". $path . "<br />";
                    }
                    else {
                        unlink($path);
                    }
                }
            }
            
            // 刪除SQLite檔案
            $sqlite_path = get_root_path("application/db/php-file-converter.sqlite.db");
            $sqlite_orig_path = get_root_path("application/db/php-file-converter.sqlite.orig.db");
            
            if ($debug) {
                echo "copy: ". $sqlite_orig_path . "<br />";
                echo "copy to: ". $sqlite_path . "<br />";
            }
            else {
                copy($sqlite_orig_path, $sqlite_path);
            }
            
            $this->load->view('component/header');
            $this->load->view('reset_view');
            $this->load->view('component/footer');
        }
        
        /**
         * 初始化目錄
         */
        private function _init_dir() {
            // @TODO sqlite目錄
            // @TODO sqlite檔案權限改變
            // @TODO convert-files目錄
        }
        
        /**
         * 顯示訊息
         * @param {String} $message
         */
        private function _message($message) {
            #$view_data["page_title"] = $this->lang->line("page_title");
            $view_data["message"] = $message;
            $this->load->view('component/header', $view_data);
            $this->load->view('component/error', $view_data);
            $this->load->view('component/footer');
        }
}

/* End of file converter.php */
/* Location: ./application/controllers/converter.php */