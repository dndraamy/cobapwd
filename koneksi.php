<?php
    $koneksi = new mysqli('localhost', 'root', '', 'seagames_fb');
    
    if($koneksi->connect_error) {
        die("Koneksi gagal!".$koneksi->connect_error);
    }
?>