<?php

namespace Acacha\Users\Models;

use Acacha\Stateful\Contracts\Stateful;
use Acacha\Stateful\Traits\StatefulTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProgressBatch.
 *
 * @package Acacha\Users\Models
 */
class ProgressBatch extends Model implements Stateful
{
    use StatefulTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['state', 'accomplished','incidences'];

    /**
     * Progress States
     *
     * @var array
     */
    protected $states = [
        'pending'  => ['initial' => true],
        'stopped' ,
        'finished' => ['final' => true]
    ];

    /**
     * Transaction State Transitions
     *
     * @var array
     */
    protected $transitions = [
        'stop' => [
            'from' => ['pending'],
            'to' => 'stopped'
        ],
        'resume' => [
            'from' => ['stopped'],
            'to' => 'pending'
        ],
        'finish' => [
            'from' => ['pending'],
            'to' => 'finished'
        ]
    ];

    /**
     * @return bool
     */
    protected function validateFinish()
    {
        if ( ( $this->accomplished != 0 ) || ( $this->incidences !=0 ) ) return true;
        return false;
    }

    /**
     * Get the progressable associated to this batch.
     */
    public function progressable()
    {
        return $this->hasMany(UserMigration::class);
    }

}

