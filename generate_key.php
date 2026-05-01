<?php
$key = bin2hex(openssl_random_pseudo_bytes(32));
echo "encryption.key = hex2bin('" . $key . "');\n";