<?php

namespace App\Models\Membership;

enum StaffNameFormat: string
{
    case Initials = 'initials';
    case FirstNameAndLastInitial = 'firstname_lastname_initial';
    case FullName = 'fullname';
}
