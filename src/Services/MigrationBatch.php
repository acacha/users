<?php

namespace Acacha\Users\Services;

use Acacha\Users\Models\ProgressBatch;

/**
 * Class MigrationBatch.
 *
 * @package Acacha\Users\Services
 */
class MigrationBatch
{
    /**
     * Init/create Migration batch.
     *
     * @return int
     */
    public function init()
    {
        return ProgressBatch::create([]);
    }
}