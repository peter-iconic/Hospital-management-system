<?php
// includes/functions.php

// Safe HTML escape
function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
