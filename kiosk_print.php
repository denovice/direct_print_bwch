<?php
/* Change to the correct path if you copy this example! */
require 'autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/**
 * direct printer ini menggunakan library escpos-php yang dibuat oleh Michael Billington (mike42)
 * library dapat di download di : https://github.com/mike42/escpos-php
 * panduan installasi / penggunaan dapat di lihat di :
 * https://github.com/mike42
 * https://mike42.me/blog/print-larger-or-smaller-text-on-a-thermal-receipt-printer
 * https://mike42.me/blog/tag/escpos-php
 * https://dendy.staff.ugm.ac.id/simple-pos-direct-printing-windows/
 */

try {

    // B : new by cv 040419

    /* get parsingan variable */
    $prs        = str_replace("^^^", " ", $_GET['prs']);
    $atname     = str_replace("^^^", " ", $_GET['atname']);
    $antrian    = str_replace("^^^", " ", $_GET['antrian']);
    $jam        = str_replace("^^^", " ", $_GET['jam']);
    $printer_name = str_replace("^^^", " ", $_GET['printer_name']);

    /* Initialize printer */
    $connector = new WindowsPrintConnector($printer_name);
    $printer = new Printer($connector);
    $printer -> initialize();

    /* Header : Nama Tombol */
    $printer -> feed();
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    //$printer -> text("Codeigniter Cafe\n");
    $printer -> text($prs."\n");
    $printer -> selectPrintMode();
    //$printer -> setUnderline(1);
    /* Deskripsi Tombol */
    //$printer -> text("Facebook Group Indonesia\n");
    $printer -> text($atname."\n");
    //$printer -> setUnderline(0);
    $printer -> feed();

    
    /* Content : Nomor Antrian */
    //$printer -> feed();
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED | Printer::MODE_DOUBLE_WIDTH);
    //$printer -> setTextSize($width, $height); 
    //$printer -> setTextSize(8, 8); 
    $printer -> text($antrian."\n");
    $printer -> selectPrintMode();
    $printer -> feed();

    /*$printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED | Printer::MODE_DOUBLE_WIDTH);
    $printer -> setTextSize(4, 8);
    //$printer -> setTextSize(8, 8);
    $printer -> text("* 46 *");
    $printer -> selectPrintMode();
    $printer -> feed();*/

    /*$printer -> setJustification(Printer::JUSTIFY_CENTER);
    for ($i = 1; $i <= 8; $i++) {
        $printer -> setTextSize($i, $i);
        $printer -> text("\ncontoh : ".$i);
    }
    $printer -> text("\n");*/

    /* Footer : Waktu Cetak */
    $printer -> feed(2);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text($jam . "\n");

    
    /* Cut the receipt and open the cash drawer */
    $printer -> cut();
    $printer -> pulse();
    $printer -> close();

    // E : new by cv 040419

} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}