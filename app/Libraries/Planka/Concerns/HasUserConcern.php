<?php

namespace App\Libraries\Planka\Concerns;

use App\Models\Membership\User\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psy\Util\Str;

trait HasUserConcern
{
    /**
     * @param User $user
     * @return array
     * @throws GuzzleException
     */
    public function createUserAccount(User $user): array
    {
        $pwd = \Illuminate\Support\Str::random(20);

        $res = $this->_sendRequest('POST', 'users', [
            'form_params' => [
                'name' => $user->username,
                'username' => $user->id . \Illuminate\Support\Str::random(3),
                'email' => \Illuminate\Support\Str::random() . $user->email,
                'password' => $pwd,
            ],
        ]);

        $res['item']['password'] = $pwd;

        return $res;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getUserAccounts()
    {
        return $this->_sendRequest('GET', 'users');
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getUserById(int $id)
    {
        return $this->_sendRequest('GET', 'users' . $id);
    }
}
