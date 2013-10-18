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

            $config['allowed_types'] = '*';
            $config['max_size'] = $this->config->item("max_size");
            $config['encrypt_name'] = TRUE;
            
            $this->load->library('upload', $config);
            $this->upload->do_upload('bitstream');
            
            $upload_data = $this->upload->data();
            $internal_name = $upload_data['file_name'];
            $original_name = $upload_data['orig_name'];
            
            $bitstream = new Bitstream();
            $bitstream->set_field("original_name", $original_name);
            $bitstream->set_field("internal_name", $internal_name);
            $bitstream->set_field("type", "uploaded");
            
            $bitstream->update();
            $bitstream_id = $bitstream->get_id();
            
            $this->puli_log->create_log($bitstream, "upload");
            
            // 觸動轉檔工作
            //$this->_start_convert_cli();
            
            // 記錄完畢，header去wait
            $this->wait($bitstream_id);
        }
        
        /**
         * 顯示轉換中的等待訊息
         * @param int $bitream_id
         */
	public function wait($bitstream_id) {
            
            $bitstream = new Bitstream($bitstream_id);
            
            //sleep(1);
            $is_convert_completed = $bitstream->is_convert_completed();
            
            if ($is_convert_completed === FALSE) {
                //還沒轉換完成喔
                
                $name = $bitstream->get_original_name();
                $view_data["page_title"] = $this->lang->line("page_title");

                $view_data["message"] = $name . $this->lang->line("wait");
                $view_data["start_convert"] = base_url('converter/start_convert');
                $view_data["wait_uri"] = base_url('converter/'. $bitstream_id);
                $view_data["status_uri"] = base_url('status/'. $bitstream_id);
                $view_data["download_uri"] = base_url('download/'. $bitstream_id);
                $view_data["deleted_uri"] = base_url('deleted/'. $bitstream_id);
                $view_data["wait_reload_interval"] = $this->config->item("wait_reload_interval");

                $this->load->view('component/header', $view_data);
                $this->load->view('wait_view', $view_data);
                $this->load->view('component/footer');
            }
            else {
                //轉換完成哩
                
                $converted_bitstream = $bitstream->get_converted_bitstream();
                
                $this->puli_log->create_log($bitstream, "delete");
                
                $bitstream->delete();
                
                $this->download($converted_bitstream);
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
            
            $this->load->view("component/message", array(
                "message" => $status
            ));
        }


        /**
         * 啟動轉換器，供命令列CLI使用
         */
        public function start_convert() {
            $this->load->library("Converter");
            
            $converter = new Converter();
            $converter->start_convert();
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
            
            if (is_int($bitstream_id)) {
                $bitstream = new Bitstream($bitstream_id);
            }
            else {
                $bitstream = $bitstream_id;
            }
            
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

            download_contents($filepath, $type, $name);
        }
        
        /**
        * SAVR 10/20/06 : force file download over SSL for IE
        * BIP  09/17/07 : inserted and tested for ProjectPier 
        * Was:
        * function download_contents($content, $type, $name, $size, $force_download = false) {
        */
       private function download_contents($content, $type, $name) {
          $chunksize = 1*(1024*1024); // how many bytes per chunk
          $buffer = '';
          $handle = fopen($content, 'rb');

          $size = filesize($content);
          //echo $size;
          download_headers($name, $type, $size);

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
        private function download_headers($name, $type, $size, $force_download = true) {
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
            $view_data["page_title"] = $this->lang->line("page_title");
            $view_data["message"] = $this->lang->line("deleted");
            $this->load->view('component/header', $view_data);
            $this->load->view('component/message', $view_data);
            $this->load->view('component/footer');
        }
}

/* End of file converter.php */
/* Location: ./application/controllers/converter.php */