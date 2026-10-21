<?php
// rechnet jährliche abos auf einen monat um
function monatskosten($preis, $intervall)
{
    if ($intervall == "jährlich") {
        return $preis / 12;
    }
    return $preis;
}

// zeigt einen betrag als 13,99 €
function euro($betrag)
{
    return number_format($betrag, 2, ',', '.') . " €";
}