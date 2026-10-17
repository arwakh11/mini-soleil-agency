<?php

namespace App\Enum;

enum MediaType: string
{
    case PHOTOS = 'Photos';
    case VIDEOS = 'Videos';
    case BOTH = 'Both';
}
