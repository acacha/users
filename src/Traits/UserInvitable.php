<?php

namespace Acacha\Users\Traits;

use Acacha\Users\Models\UserInvitation;

/**
 * Class Invitable.
 *
 * @package Acacha\Users\Traits
 */
trait UserInvitable
{
    /**
     * Get the invitation record associated with the user.
     */
    public function invitation()
    {
        return $this->hasOne(UserInvitation::class);
    }
}