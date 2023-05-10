<?php

namespace App\Libraries\DFS;

use Illuminate\Support\Str;

class DFSChart
{
    public $id;
    public $type;
    public $name;
    public $airac;
    public $revised_at;
    public $link;

    function __construct($type, $name, $airac, $revised_at, $link)
    {
        $this->id = (string) Str::uuid();
        $this->type = $type;
        $this->name = $name;
        $this->airac = $airac;
        $this->revised_at = $revised_at;
        $this->link = $link;
    }
}
