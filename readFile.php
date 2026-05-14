<?php

// Store the file name into variable


$filename = $_GET['file'];

// $file = '1.pdf';
// $filename = '1.pdf';

$exts = array('gif', 'png', 'jpg', 'pdf', 'jpeg', 'jfif');

if (in_array(strtolower(end(explode('.', $filename))), $exts)) {

    if (strtolower(end(explode('.', $filename))) == 'jpg' || strtolower(end(explode('.', $filename))) == 'jpeg') {
        // header("Content-type: application/pdf");
        header("Content-type: image/jpeg");
    } else if (strtolower(end(explode('.', $filename))) == 'png') {
        header("Content-type: image/png");
    } else if (strtolower(end(explode('.', $filename))) == 'pdf') {
        header("Content-type: application/pdf");
    } else if (strtolower(end(explode('.', $filename))) == 'jfif') {
        header("Content-type: image/pjpeg");
    } else if (strtolower(end(explode('.', $filename))) == 'gif') {
        header("Content-type: image/gif");
    }
}

header("Content-Length: " . filesize($filename));

readfile($filename);
