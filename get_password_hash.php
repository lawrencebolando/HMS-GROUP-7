<?php
// Simple script to generate password hash
// Run: php get_password_hash.php

echo password_hash('it1234', PASSWORD_DEFAULT);
echo "\n";
echo "\nCopy the hash above and use it in the SQL file.\n";

