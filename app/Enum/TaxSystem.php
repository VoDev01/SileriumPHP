<?php
namespace App\Enum;

enum TaxSystem : string
{
    case USN = "УСН";
    case OSNO = "ОСНО";
    case ESHN = "ЕСХН";
}