<?php

namespace App\Enum;

enum QuoteRequestStatus: string
{
    case PENDING = 'Pending';
    case ACCEPTED = 'Accepted';
    case REJECTED = 'Rejected';
}
