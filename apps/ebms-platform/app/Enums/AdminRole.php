<?php

namespace App\Enums;

enum AdminRole: string
{
    case Superadmin = 'superadmin';
    case Admin      = 'admin';
    case Staff      = 'staff';
}
