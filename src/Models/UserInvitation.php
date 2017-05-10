<?php

namespace Acacha\Users\Models;

use Illuminate\Database\Eloquent\Model;

use Acacha\Stateful\Traits\StatefulTrait;
use Acacha\Stateful\Contracts\Stateful;

/**
 * Class UserInvitation.
 *
 * @package Acacha\Users\Models
 */
class UserInvitation extends Model implements Stateful
{
    use StatefulTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','state'];

    /**
     * Transaction States
     *
     * @var array
     */
    protected $states = [
        'pending'  => ['inital' => true],
        'accepted' => ['final' => true]
    ];

    /**
     * Transaction State Transitions
     *
     * @var array
     */
    protected $transitions = [
        'accept' => [
            'from' => ['pending'],
            'to' => 'accepted'
        ]
    ];

//    /**
//     * @return bool
//     */
//    protected function validateAccept()
//    {
//        if ($this->user_id != null && $this->user()) return true;
//        return false;
//    }
}

