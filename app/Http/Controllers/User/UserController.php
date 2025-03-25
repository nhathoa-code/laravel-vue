<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReserveRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Book\Book;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'type'=>'required|string|in:patron,staff',
            'q' => 'nullable|string',
            'library' => 'nullable|exists:libraries,id'
        ]);
        return $this->userModel->getUsers($validated,5);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $this->userModel->storeUser($validated);
        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }

    public function show(User $user) 
    {
        $relations = [
            'userMeta.library',
            'holds.book','holds.pickupLocation',
            'loans.bookItem.book','loans.bookItem.homeLibrary',
        ];
        return $user->load($relations);
    }

    public function showByCard(Request $request)
    {
        $validated = $request->validate([
            'card' => 'required|string|exists:user_meta,card_number'
        ]);
        return $this->userModel->getByCard($validated['card']);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->updateUser($validated);
        return response()->json([
            'message' => 'User updated successfully'
        ], 200);
    }

    public function changePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $user->changePassword($validated);
        return response()->json([
            'message' => 'Username and/or password updated successfully'
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function getLoggedInUser(Request $request)
    {
        $user = $request->user();
        return $user->load('lists');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'type'=>'required|string|in:patron,staff',
            'q' => 'required|string'
        ]);
        return $this->userModel->search($validated);
    }

    public function reserve(ReserveRequest $request,User $user, Book $book)
    {
        $validated = $request->validated();
        if($user->checkHold($book)){
            return response()->json([
                'message' => 'The patron already holds this book'
            ], 400);
        }
        $user->reserve($book, $validated);
        return response()->json([
            'message' => 'Reserve has placed successfully'
        ], 200);
    }
}
