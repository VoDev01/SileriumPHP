<?php
namespace App\Enum;

enum SortOrder: int
{
    case NAME_ASC = 1;
    case NAME_DESC = 2;
    case POP_ASC = 3;
    case POP_DESC = 4;
    case PRICE_ASC = 5;
    case PRICE_DESC = 6;
}