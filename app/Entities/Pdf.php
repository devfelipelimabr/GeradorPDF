<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pdf extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at'];
    protected $casts   = [];
}
