<?php

namespace App\Http\Controllers;

use App\Models\UserInformation;
use App\Http\Requests\StoreUserInformationRequest;
use App\Http\Requests\UpdateUserInformationRequest;

class UserInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserInformationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInformation $userInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserInformation $userInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserInformationRequest $request, UserInformation $userInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInformation $userInformation)
    {
        //
    }
}
