<?php

namespace App\Enums;

enum ProductStatus: string
{
    case DRAFT = "Draft";
    case TRASH = "Trash";
    case PUBLISHED = "Published";
}
