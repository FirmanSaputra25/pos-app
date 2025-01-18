<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function viewSalesReport(User $user)
    {
        return $user->role === 'admin'; // Pastikan role admin yang bisa akses
    }

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
}