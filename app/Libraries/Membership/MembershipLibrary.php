<?php

namespace App\Libraries\Membership;

use App\Libraries\Forum\XenForoLibrary;
use App\Libraries\Gitlab\GitlabLibrary;
use App\Models\Membership\User\User;
use Illuminate\Support\Facades\Log;

class MembershipLibrary
{
    public static function handleMembershipChange(User $user)
    {
        $user = $user->refresh();
        $user = $user->load('settings', 'userData', 'roles');
        # TODO: Handle all changes that might have triggered this function

        // 1. Handle forum permission / role assignment
        // Call forum library to sync changes to forum
        XenForoLibrary::updateForumAccount($user);
        // 2. Handle git access
        GitlabLibrary::checkNAVAssignments($user);
        // 3. Handle other stuff
        Log::info('[MembershipLibrary::handleMembershipChange]::' . $user->id . '::Membership Update Triggered!');
    }
}
