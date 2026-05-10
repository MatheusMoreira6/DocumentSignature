<?php

namespace App\Controllers;

use App\Core\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->layout('profile/index', [], true);
    }
}