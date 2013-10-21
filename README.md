PHP File Converter
==================

PHP File Converter是一個線上轉檔工具。它的功能為供使用者以瀏覽器上傳來源檔案、伺服器自動轉換為目標檔案、引導使用者下載目標檔案。

- 專案首頁：[GitHub: php-file-converter](https://github.com/pulipulichen/php-file-converter)
- 問題回報：[Report New Issus](https://github.com/pulipulichen/php-file-converter/issues/new) (需要GitHub帳號，可免費註冊帳號)
- 作者網誌：[布丁布丁吃什麼？](http://pulipuli.blogspot.tw/)

##系統特色

- 以伺服器作業系統的命令列進行轉檔。只要伺服器可以使用命令列CLI (command line interface)進行轉檔，就可以用PHP File Converter來轉檔。
- 伺服器端可設定上傳檔案允許大小、格式，避免惡意檔案上傳
- 伺服器端可設定轉檔輸入的參數
- 伺服器端可設定保留轉檔數量，避免使用者上傳過多檔案而導致伺服器空間不足
- 系統是基於[CodeIgniter 2.1.4](http://www.codeigniter.org.tw/)，採MVC架構開發
- 以SQLite作為資料庫，不需要額外搭配其他資料庫設定

##需求環境

+ PHP 5.5.3
+ SQLite 3
+ 硬碟空間：建議100MB以上
+ 測試環境[XAMPP 1.8.1](http://www.apachefriends.org/zh_tw/xampp.html)

##設定轉換指令

請修改伺服器的程式碼：" **[php-file-converter]/application/config/converter.php**"。參考檔案內的說明來進行設定。

## 轉換檔案使用說明

**TODO**

---

##待做功能

- 提供OpenVZ虛擬機器下載
- 提供JSONP API：讓其他系統可以用AJAX跨網域的方式上傳檔案、下載檔案，而不需要在自己的伺服器中建置轉檔工具。
- 設定IP白名單與黑名單：限定許可使用者範圍
- 設定登入才能使用

如果您想要這些功能或是有其他建議，歡迎到[問題回報](https://github.com/pulipulichen/php-file-converter/issues/new)中反映（需要GitHub帳號，可免費註冊）。

##相關工具
Online Markdown Editor: [Minimalist Online Markdown Editor](http://markdown.pioul.fr/)