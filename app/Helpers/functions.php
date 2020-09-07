<?php

/**
 * Remove todos os caracteres que não forem números de uma determinada string.
 * 
 * @param   string  $string
 * @return  string
 */
function onlyNumbers(string $string): string
{
    
    return preg_replace("/[^0-9]/", "", $string);
}