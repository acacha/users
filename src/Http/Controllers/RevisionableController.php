<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\TrackModelRequest;
use Venturecraft\Revisionable\Revision;

/**
 * Class RevisionableController.
 *
 * @package Acacha\Users\Http\Controllers
 *
 */
class RevisionableController extends Controller
{
    /**
     * Obtain tracking info for a Model.
     *
     * @param TrackModelRequest $request
     * @return array
     */
    public function trackModel(TrackModelRequest $request)
    {
        $model = $request->input('model');
        $revisions = Revision::where(['revisionable_type' => $model])
            ->orderBy('created_at','desv')->get();

        $result = [];
        $lastDate = '';
        foreach ($revisions as $revision) {
            $date = $revision->created_at->toDateString();
            if ($lastDate != $date) {
                $result[] = [
                    'time' => $revision->created_at->toDateString(),
                    'type' => 'time-label'
                ];
            }
            $result[] = [
                'time' => $revision->created_at->toTimeString(),
                'icon' => 'fa-user',
                'iconBackground' => $this->getIconBackground($revision),
                'header' => $this->getMessage($revision)

            ];
            $lastDate = $date;
        }

        return $result;


    }

    /**
     * Get icon background.
     *
     * @param $revision
     * @return string
     */
    private function getMessage($revision)
    {
        switch ($revision->key) {
            case 'created_at':
                return $this->getResponsibleInfo($revision) . ' created user '
                    . $this->getAffectedResourceName($revision) . ' (' . $revision->revisionable_id . ')';
            case 'deleted_at':
                return $this->getResponsibleInfo($revision) . ' deleted user '
                    . $this->getAffectedResourceName($revision) . ' (' . $revision->revisionable_id . ')';
            default:
                return $this->getResponsibleInfo($revision) . ' changed user '
                    . $this->getAffectedResourceName($revision) . ' (' . $revision->revisionable_id . ')'
                    . ' field ' .  $revision->fieldName() . ' from ' .$revision->oldValue() . ' to ' .
                    $revision->newValue();
        }
    }

    /**
     * Get responsible info.
     *
     * @param $revision
     * @return string
     */
    private function getResponsibleInfo($revision)
    {
        $username = $this->getResponsibleUsername($revision);
        return '<span title="'. $username . ' (' . $revision->user_id  . ')' . '" class="responsibleUser" 
            model="' . $revision->revisionable_type . '">' . $username . '</span>';
    }

    /**
     * Get responsible user name.
     *
     * @param $revision
     * @return string
     */
    private function getResponsibleUsername($revision)
    {
        if ($revision->userResponsible()) {
            return $revision->userResponsible()->name;
        }
        return 'Non existing user';
    }

    /**
     * Get affected resource name.
     *
     * @param $revision
     * @return string
     */
    private function getAffectedResourceName($revision)
    {
        if ($revision->historyOf()) {
            return $revision->historyOf()->name;
        }
        if ($revision->key == 'deleted_at') {
            $oldUser = json_decode($revision->old_value);
            if($oldUser) {
                return $oldUser->name;
            }
        }
        return 'Non existing user';
    }

    /**
     * Get icon background.
     *
     * @param $revision
     * @return string
     */
    private function getIconBackground($revision)
    {
        switch ($revision->key) {
            case 'created_at':
                return 'bg-green';
            case 'deleted_at':
                return 'bg-red';
            default:
                return 'bg-yellow';
        }
    }
}