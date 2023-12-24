<?php

namespace App\Enums;

enum SeedlingServiceStatuses: string
{
    use EnumMethods;

    case SEEDS_NOT_RECEIVED = 'لم يتم استلام البذور';
    case SEEDS_RECEIVED = 'تم استلام البذور';
    case GERMINATION_COMPLETED = 'تم التشتيل';
    case READY_FOR_PICKUP = 'جاهز للإستلام';
    case DELIVERED = 'تم التسليم';
}
