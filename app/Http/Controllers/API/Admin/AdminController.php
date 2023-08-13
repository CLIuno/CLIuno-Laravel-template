<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Models\UserInformation;
use App\Models\VerifyAccount;
use App\Traits\HasResetPsOrVerifyAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class AdminController extends Controller
{
    /**
     *
     * @return AnonymousResourceCollection
     */
    public function getAdmins()
    {
        $admins = Admin::with('roles')->get();
        return AdminResource::collection($admins);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrentAdmin(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'role' => ['required', 'string', 'max:255'],
        ]);
        // if role exists
        if (!in_array($request->role, ['ADMIN', 'OPERATOR'])) {
            return response()->json(['message' => 'invalid role'], 400);
        }

        // Create a Temp UserInformation record
        $tempUserInformation = UserInformation::factory()->create();

        /** @var Admin|HasRoles|HasResetPsOrVerifyAccount $admin */
        $admin = Admin::query()->create([
            'username' =>
                'Admin' .
                Str::upper(Str::substr($request->email, 0, 1)) .
                Str::substr(Str::lower(explode('@', $request->email)[0]), 1),
            'email' => Str::lower($request->email),
            'password' => Hash::make(Str::random(40)), // random
            'user_information_id' => $tempUserInformation->id,
        ]);

        $admin->assignRole($request->role);

        event(new Registered($admin));

        $admin->sendEmail(VerifyAccount::class);

        return response()->json(['message' => 'Admin created successfully.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
