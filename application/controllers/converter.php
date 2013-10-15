<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Converter
 * 
 * 
 */
class Converter extends CI_Controller {

        /**
         * @var Log
         */
        private $log;
    
        /**
         * 預先載入一些函式庫
         */
        public function __construct() {
            parent::__construct();
            
            $this->load->library("object/log");
            $this->log = new Log();
            
            $this->load->library("object/bitstream");
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
            
            $this->log->create_log($bitstream, "upload");
            
            // 觸動轉檔工作
            $this->start_convert();
            
            // 記錄完畢，header去wait
            $this->wait($bitstream_id);
        }
        
        /**
         * 顯示轉換中的等待訊息
         * @param int $bitream_id
         */
	public function wait($bitstream_id) {
            
            $bitstream = new Bitstream($bitstream_id);
            
            sleep(1);
            $is_convert_completed = $bitstream->is_convert_completed();
            
            if ($is_convert_completed === FALSE) {
                //還沒轉換完成喔
                
                $name = $bitstream->get_original_name();
                $view_data["page_title"] = $this->lang->line("page_title");

                $view_data["message"] = $name . $this->lang->line("wait");
                $view_data["wait_uri"] = base_url('converter/'. $bitstream_id);
                $view_data["wait_reload_interval"] = $this->config->item("wait_reload_interval");

                $this->load->view('component/header', $view_data);
                $this->load->view('wait_view', $view_data);
                $this->load->view('component/footer');
            }
            else {
                //轉換完成哩
                
                $converted_bitstream = $bitstream->get_converted_bitstream();
                
                $this->log->create_log($bitstream, "delete");
                
                $bitstream->delete();
                
                $this->download($converted_bitstream->get_id());
            }
	}
        
        /**
         * 下載檔案
         * @param int $bitstream_id
         */
        public function download($bitstream_id) {
            
            $bitstream = new Bitstream($bitstream_id);
            
            // 如果bs已經被刪除，轉移到錯誤訊息
            if ($bitstream->is_deleted()) {
                $this->deleted($bitstream_id);
            }
            
            // 記錄檔案下載
            
            
            // 輸出檔案
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