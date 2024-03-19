<?php

namespace App\Http\Integrations\Nivoda\Enums;

enum DiamondOrderType: string
{
    case CREATED_AT = 'createdAt';
    case PRICE = 'price';
    case DISCOUNT = 'discount';
    case SIZE = 'size';
    case NONE = 'none';
    case INSERT = 'insert';
}
