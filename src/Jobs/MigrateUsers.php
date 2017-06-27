<?php

namespace Acacha\Users\Jobs;

use Acacha\Users\Services\UserMigrations;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class MigrateUsers
 *
 * @package Acacha\Users\Jobs
 */
class MigrateUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Users to migrate.
     *
     * @var $usersToMigrate
     */
    public $usersToMigrate;

    /**
     * Migration batch.
     *
     * @var $usersToMigrate
     */
    public $batch;

    /**
     * MigrateUsers constructor.
     *
     * @param $usersToMigrate
     * @param $batch
     */
    public function __construct($usersToMigrate, $batch)
    {
        $this->usersToMigrate = $usersToMigrate;
        $this->batch = $batch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserMigrations $service)
    {
        $service->migrate($this->usersToMigrate, $this->batch);
    }

}
