<?php

namespace Modules\User\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case Customer = 'customer';
}
