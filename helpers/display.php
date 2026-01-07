<?php

// Mapeig global de categories (fixes)
function getCategoriaNoms() {
    return [
        1 => 'Haikus',
        2 => 'Cites',
        3 => 'Pensaments',
        4 => 'Altres'
    ];
}

// Formata una data MySQL ('Y-m-d H:i:s') com '17 oct, 2025' en català
function formatDateCa($dateString) {
    if (empty($dateString)) return '';
    
    $mesos = [
        1 => 'gen', 2 => 'feb', 3 => 'mar', 4 => 'abr',
        5 => 'mai', 6 => 'jun', 7 => 'jul', 8 => 'ago',
        9 => 'set', 10 => 'oct', 11 => 'nov', 12 => 'des'
    ];
    
    $timestamp = strtotime($dateString);
    if ($timestamp === false) return $dateString;
    
    $dia = date('j', $timestamp);
    $mes = $mesos[(int)date('n', $timestamp)];
    $any = date('Y', $timestamp);
    
    return "$dia $mes, $any";
}