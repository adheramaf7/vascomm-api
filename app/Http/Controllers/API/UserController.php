<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\GetUserRequest;
use App\Http\Requests\API\SaveUserRequest;
use App\Http\Requests\API\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ResponseFormatter;

    public function index(GetUserRequest $request)
    {
        $data = User::query()
            ->when($request->query('search'), fn (Builder $query) => $query->where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($request->query('search')) . '%'))
            ->when(!is_null($request->query('skip')) && !is_null($request->query('take')), fn (Builder $query) => $query->skip($request->query('skip'))->take($request->query('take')))
            ->get();

        return $this->formatResponse(data: UserResource::collection($data));
    }

    public function store(SaveUserRequest $request)
    {
        $user = User::create($request->validated());

        return $this->formatResponse(code: 201, message: 'Created', data: UserResource::make($user));
    }

    public function show(User $user)
    {
        return $this->formatResponse(data: UserResource::make($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return $this->formatResponse(message: 'Updated', data: UserResource::make($user));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->formatResponse(message: 'Deleted');
    }
}
