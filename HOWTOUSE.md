# direct printer KIOS-K RS BWCH
- direct printer ini menggunakan library escpos-php yang dibuat oleh Michael Billington (mike42)
- library dapat di download di : https://github.com/mike42/escpos-php
- keterangan lebih lanjut https://mike42.me/blog/tag/escpos-php


# bahan-bahan yang di perlukan
- printer Epson TM-T88IV (beberapa printer yang support dengan library ESCPOS ini bisa dilihat di https://github.com/mike42/escpos-php)
- driver printer Epson TM-T88IV
- Winrar
- PHP 5.4 or above
- XAMPP with PHP 5.6.8 (tested with xampp-win32-5.6.8-0-VC11-installer)
- file "escpos_php_bwch.rar" 


# step by step installasi
- install driver TM-T88IV di Komputer yang akan dipasang printer
- install xampp 
- copy file "escpos_php_bwch.rar" ke directory C:\xampp\htdocs\ kemudian extract here dengan winrar
- turn windows defender firewall off (matikan firewall windows defender)
- matikan firewall dan antivirus yang terpasang
- set auto start apache
- set startup xampp
- restart apache
- restart komputer


# Cara set auto start apache (XAMPP control panel v3.2.1)
- buka XAMPP dan jangan start apache nya
- pilih config yang ada di pojok kanan atas
- lalu centang "Apache" dan "Start Control Panel Minimized"
- klik save


# Set up driver printer 
- Control Panel -> Hardware and Sound -> Devices and Printers , pilih driver printer TM
- klik kanan, pilih printer properties
- tab "General" rename nama printer nya menjadi "PRINT_KIOSK" atau sesuai dengan config di control panel aplikasi teramedik
- tab "Sharing" rename share nama printer nya menjadi "PRINT_KIOSK" atau sesuai dengan config di control panel aplikasi teramedik


# Cara set Start Up XAMPP di WIndows 10
- buka CMD, dengan cara tekan Windows + R , lalu ketik "regedit" dan klik OK
- kemudian cari key berikut : "HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run"
- jika telah ketemu maka klik kanan pada halaman kosong, pilih "New" >> "String Value"
- double klik di xampp yang tadi kita buat , lalu masukan C:\xampp\xampp-control.exe
- setelah itu restart komputer
- kemudian buka browser dan ketik http://localhost/phpmyadmin/


# Set up config Control Panel di Master Data aplikasi Teramedik
- Masuk ke modul Master Data -> Control Panel, ketikkan "kios-k" di kolom Description
- IP Aplikasi KIOS-K 1 : adalah IP Address untuk komputer yang digunakan untuk KIOS-K
- IP printer KIOS-K 1 : adalah IP Address untuk komputer yang terhubung dengan printer untuk cetak nomor antrian
- Nama sharing printer KIOS-K 1 : adalah nama printer yang digunakan untuk cetak nomor antrian
- Password komputer KIOS-K 1 : passwor komputer yang tehubung dengan printer untuk cetak nomor antrian


# Jika belum ada config nya di database

/* Data for the 'configs_rs' table  (Records 1 - 1) */

INSERT INTO configs_rs ("confname", "data", "description", "data_type", "is_editable", "cgid", "is_show")
VALUES 
  (E'kiosk_ip_1', E'192.168.0.30', E'IP printer KIOS-K 1', E'IP Address (192.168.x.x)', True, 1, True);


/* Data for the 'configs_rs' table  (Records 1 - 1) */

INSERT INTO configs_rs ("confname", "data", "description", "data_type", "is_editable", "cgid", "is_show")
VALUES 
  (E'kiosk_printer_name_1', E'PRINT_KIOSK', E'Nama sharing printer KIOS-K 1', E'string', True, 1, True);


/* Data for the 'configs_rs' table  (Records 1 - 1) */

INSERT INTO configs_rs ("confname", "data", "description", "data_type", "is_editable", "cgid", "is_show")
VALUES 
  (E'kiosk_pc_pass_1', E'-', E'Password komputer KIOS-K 1', E'string', True, 1, True);
  
  
INSERT INTO configs_rs ("confname", "data", "description", "data_type", "is_editable", "cgid", "is_show")
VALUES 
  (E'kiosk_ip_app_1', E'192.168.0.30', E'IP Aplikasi KIOS-K 1', E'IP Address (192.168.x.x)', True, 1, True);



# Konfigurasi php di aplikasi teramedik
- buka file ant_awal.inc.php
- cari sub_print_antrian
- codingan nya seperti ini :

// B : add cv 260319 autoprint with XAMPP
		$ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';

	    $kiosk_ip_app = $base->dbgetone("SELECT data FROM configs_rs WHERE confname = 'kiosk_ip_app_1'");

	    $prs = $hasil[0];
	    $antrian = $kode.''.$hasil[1];
	    $jam = $tanggal;

	    if($this->S['userdata']->pid==1) echo "#ipaddress=$ipaddress #kiosk_ip_app=$kiosk_ip_app #prs=$prs #antrian=$antrian #jam=$jam";

	    if($ipaddress==$kiosk_ip_app){
	    	$kiosk_ip = $base->dbgetone("SELECT data FROM configs_rs WHERE confname = 'kiosk_ip_1'");
			$printer_name = $base->dbgetone("SELECT data FROM configs_rs WHERE confname = 'kiosk_printer_name_1'");
			$pass_pc = $base->dbgetone("SELECT data FROM configs_rs WHERE confname = 'kiosk_pc_pass_1'");
			
			$etiket_biru = "prs=".$prs;
			$etiket_biru .= "&antrian=".$antrian;
			$etiket_biru .= "&jam=".$jam;
			$etiket_biru .= "&atname=".$base->getLang($base->dbGetOne("SELECT sub_atname FROM ant_tombol WHERE UPPER(atname)=UPPER('".trim($hasil[0])."')"));
			$etiket_biru .= "&printer_name=".$printer_name;

			//$cmd = $base->kcfg['print_etiket_biru_cmd'];
			//$cmd = "curl --request GET 'http://".$kiosk_ip."/print_kiosk.php?%s' >/dev/null";
			//$cmd = "curl --request GET 'http://".$kiosk_ip."/escpos_php_master/kiosk_print.php?%s' >/dev/null";
			$cmd = "curl --request GET 'http://".$kiosk_ip."/escpos_php_bwch/kiosk_print.php?%s' >/dev/null";
			
			$ticket= str_replace(" ","^^^",$etiket_biru);

			//echo $cmd." ".$ticket;
			$cmd = sprintf($cmd,$ticket);
			
			$a = array();
			$ret = 0;
			@exec($cmd,$a,$ret);
			
			if($this->S['userdata']->pid==1) echo "<br>#$cmd";
	    }
		// E : add cv 260319 autoprint with XAMPP