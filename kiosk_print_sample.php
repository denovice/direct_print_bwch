<?php
/* Change to the correct path if you copy this example! */
require 'autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

try {

    // B : new by cv 040419

    //get parsingan variable
    $prs        = str_replace("^^^", " ", $_GET['prs']);
    $antrian    = str_replace("^^^", " ", $_GET['antrian']);
    $jam        = str_replace("^^^", " ", $_GET['jam']);
    $atname     = str_replace("^^^", " ", $_GET['atname']);
    $printer_name = str_replace("^^^", " ", $_GET['printer_name']);

    $connector = new WindowsPrintConnector($printer_name);
    $printer = new Printer($connector);
    $printer -> initialize();

    /* Date is kept the same for testing */
    $date = date('D j M Y H:i:s');

    /* Name of shop */
    $printer -> feed();
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer -> text("Codeigniter Cafe\n");
    $printer -> selectPrintMode();
    $printer -> setUnderline(1);
    $printer -> text("Facebook Group Indonesia\n");
    $printer -> setUnderline(0);
    $printer -> feed();

    /* Title of receipt */
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setEmphasis(true);
    $printer -> text("Tagihan : \n");
    $printer -> setEmphasis(false);
    $printer -> feed(2);

    /* Items */
    $printer -> setEmphasis(true);
    //$printer -> text(new item(", '$'));
    $printer -> setEmphasis(false);

    $harga = 0;
    $harganya = 15000;
    //foreach ($list->result() as $r) {
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    //$printer -> text("$r->nama_item ______");
    $printer -> text("Sanmol ______");
    $printer -> text("Rp.");
    //$printer -> text("$r->harga\n");
    $printer -> text("$harganya\n");
    $printer-> feed();

    //$total = $harga + $r->harga;
    $total = $harga + $harganya;
    $harga = $total;
    //}

    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setEmphasis(true);
    $printer -> text("Total : Rp. ");
    $printer -> text($total);
    $printer -> setEmphasis(false);
    $printer -> feed();

    /* Tax and total */
    //$printer -> text($tax);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    //$printer -> text($total);
    $printer -> selectPrintMode();

    /* Footer */
    $printer -> feed(2);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("Terima Kasih sudah Menunggu :D\n");
    $printer -> text("Semangaatt !\n");
    $printer -> feed(2);
    $printer -> text($date . "\n");

    /* Cut the receipt and open the cash drawer */
    $printer -> cut();
    $printer -> pulse();
    $printer -> close();

    // E : new by cv 040419

} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
