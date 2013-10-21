PHP File Converter
==================

##簡介

PHP File Converter是一個線上轉檔工具。它的功能為供使用者以瀏覽器上傳來源檔案、伺服器自動轉換為目標檔案、引導使用者下載目標檔案。

[專案程式碼下載](https://github.com/pulipulichen/php-file-converter/archive/master.zip) 

- 作者：[布丁布丁吃布丁 Pulipuli Chen](pulipuli.chen@gmail.com)
- 專案首頁：[GitHub: php-file-converter](https://github.com/pulipulichen/php-file-converter)
- 問題回報：[Report New Issus](https://github.com/pulipulichen/php-file-converter/issues/new) (需要GitHub帳號，可免費註冊帳號)
- 作者網誌：[布丁布丁吃什麼？](http://pulipuli.blogspot.tw/) [如要直接聯繫作者，請在網誌上留言](https://www.blogger.com/comment.g?blogID=16607461&postID=113544406852218769)。

###開發背景

本系統中指的「轉檔」是指「轉換程式」。舉例來說，ffmpeg可以將影片轉成指定格式；pdf2htmlEx可以將PDF檔案轉換成HTML格式。本系統提供一個框架，建置者可以設定自己的轉檔程式，
再提供使用者可以利用瀏覽器來使用轉檔功能。

很多轉檔程式都提供CLI介面，可以以系統指令進行轉檔。可是這對於不懂得在作業系統中安裝轉換程式的人來說並不方便。
當轉檔程式的安裝程序非常複雜、或是轉檔需要仰賴高等級的硬體來執行時，集中在一臺伺服器提供轉檔功能就會是比較好的方案。

現在網際網路上提供線上轉檔的服務很多，但是我卻找不到可以讓我自訂轉檔程式進行轉檔的系統。
我需要自行架設一個線上轉檔服務，並設定特定的轉檔程式來使用。
因為找不到，所以乾脆自行開發。這就是PHP File Converter的由來。

##系統特色

- 以伺服器作業系統的命令列進行轉檔。只要伺服器可以使用命令列CLI (command line interface)進行轉檔，就可以用PHP File Converter來轉檔
- 伺服器端可設定上傳檔案允許大小、格式，避免惡意檔案上傳
- 伺服器端可設定轉檔輸入的參數
- 伺服器端可設定保留轉檔數量，避免使用者上傳過多檔案而導致伺服器空間不足
- 檔案的上傳、轉換與下載會在系統留下記錄，包括檔案名稱、使用者IP與時間
- 系統是基於[CodeIgniter 2.1.4](http://www.codeigniter.org.tw/)，採MVC架構開發
- 以SQLite作為資料庫，不需要額外搭配其他資料庫設定

##需求環境

+ PHP 5.5.3
+ SQLite 3
+ 硬碟空間：建議100MB以上
+ 測試環境[XAMPP 1.8.1](http://www.apachefriends.org/zh_tw/xampp.html)

[專案程式碼下載](https://github.com/pulipulichen/php-file-converter/archive/master.zip) 

##設定轉換指令

請修改伺服器的程式碼：" **[php-file-converter]/application/config/converter.php**"。參考檔案內的說明來進行設定。

###系統開發備註

- bitstream 檔案：指使用者上傳的檔案
- converter 轉換器：指本系統進行轉換的核心功能

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

- Online Markdown Editor: [Minimalist Online Markdown Editor](http://markdown.pioul.fr/)
- 目標測試轉換器：[pdf2htmlEX](https://github.com/coolwanglu/pdf2htmlEX)