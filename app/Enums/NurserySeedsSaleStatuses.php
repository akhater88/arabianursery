<?php

namespace App\Enums;

enum NurserySeedsSaleStatuses: string
{
    use EnumMethods;

    case DRAFT_SALE = 'لم يتم تسليم البذور';
    case SALE = 'تم تسليم البذور';
}
