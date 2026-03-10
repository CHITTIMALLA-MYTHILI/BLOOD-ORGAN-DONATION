<?php
function renderHeader(string $title): void
{
    echo "<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>{$title}</title><link rel='stylesheet' href='/public/assets/css/styles.css'></head><body>";
    echo "<header><h2>Organ & Blood Donation Management System</h2><nav>";
    echo "<a href='/public/index.php'>Home</a><a href='/public/login.php'>Login</a><a href='/public/register.php'>Register</a>";
    echo "</nav></header><main class='container'>";
}

function renderFooter(): void
{
    echo '</main></body></html>';
}
?>
