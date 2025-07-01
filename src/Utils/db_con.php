<?php

    $host = "localhost";
    $dbname = "cta2025-2";
    $user = "postgres";
    $password = "postgres";

    $db_conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

    if (!$db_conn) {
        die("Erro ao conectar ao banco de dados: " . pg_last_error());
    }

?>