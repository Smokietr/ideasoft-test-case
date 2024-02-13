<?php
if (! function_exists('formattedPrice')) {
    function formattedPrice($price): string
    {
        return sprintf('%s TL', number_format($price, 2, ',', '.'));
    }
}
