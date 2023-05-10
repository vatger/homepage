<?php

namespace App\Libraries\Planka\Concerns;

use GuzzleHttp\Exception\GuzzleException;

trait HasProjectConcern
{
    /**
     * @throws GuzzleException
     */
    public function getProjects()
    {
        return $this->_sendRequest('GET', 'projects');
    }

    /**
     * @throws GuzzleException
     */
    public function addManager(int $projectId, int $plankaUserId)
    {
        return $this->_sendRequest('POST', 'projects/' . $projectId . '/managers', [
            'form_params' => [
                'userId' => $plankaUserId,
            ],
        ]);
    }
}
