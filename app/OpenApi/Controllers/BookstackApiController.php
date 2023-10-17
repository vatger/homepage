<?php

namespace App\OpenApi\Controllers;

use App\Libraries\BookstackLibrary;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class BookstackApiController extends ApiController
{
    /**
     *  Some docs here
     * @return false|object
     */
    #[OpenApi\Operation]
    public function bookstack()
    {
        return false;
        //return BookstackLibrary::_users_read(1234027);
        //return BookstackLibrary::_users_update(1234027, [1, 4, 8, 9]);
    }
}
