<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Book\Book;
use App\Models\Circulation\Hold;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Relations\UserRelation;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use UserRelation;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function storeUser(array $data)
    {
        $user = $this->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);
        $user->storeMeta($data);
    }

    protected function storeMeta(array $data)
    {
        $this->userMeta()->create([
            'card_number' => $data['card_number'],
            'gender' => $data['gender'],
            'birthdate' => $data['birthdate'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'registration_date' => $data['registration_date'],
            'expiration_date' => $data['expiration_date'],
            'status' => 'active',
            'library_id' => $data['library'] ?? null,
        ]);
    }

    public function updateUser(array $data)
    {
        $this->updateMeta($data);
    }

    public function changePassword(array $data)
    {
        if(!isset($data['password'])){
            $this->update([
                'username' => $data['username']
            ]);
        }else{
            $this->update([
                'username' => $data['username'],
                'password' => Hash::make($data['password'])
            ]);
        }
    }

    protected function updateMeta(array $data)
    {
        $this->userMeta()->update([
            'library_id' => $data['library'] ?? null,
        ]);
    }

    public function getUsers(array $data, $limit = 1)
    {
        if($data['type'] == 'patron'){
            $query = $this->where('role','patron');
        }elseif($data['type'] == 'staff'){
            $query = $this->where('role','!=','patron');
        }
        $query->with('userMeta.library');
        if($q = $data['q'] ?? null){
            $query->where(function($query) use($q){
                $query->where('name','like',"{$q}%")
                    ->orWhereRelation('userMeta', 'card_number', $q);
            });
        }
        if($library = $data['library'] ?? null){
            $query->whereRelation('userMeta', 'library_id', $library);
        }
        return $query->paginate($limit);
    }

    public function getByCard(string $card)
    {
        $user = $this->whereRelation('userMeta','card_number',$card)->firstOrFail();
        return $user->load(
            'userMeta',
            'loans.bookItem.book',
            'loans.bookItem.homeLibrary',
            'holds.book','holds.pickupLocation',
        );
    }

    public function checkHold(Book $book)
    {
        return Hold::where('patron',$this->id)
            ->where('book_id',$book->id)->exists();
    }

    public function countHolds()
    {
        return Hold::where('patron',$this->id)->count();
    }

    public function reserve(Book $book,array $data)
    {
        switch($data['method']){
            case 'available-item':
                $this->holds()->create([
                    'book_id' => $book->id,
                    'pickup_library' => $data['pickup_library']
                ]);
                break;
            case 'specific-item':
                $this->holds()->create([
                    'book_id' => $book->id,
                    'book_item_id' => $data['book_item_id'],
                    'pickup_library' => $data['pickup_library']
                ]);
                break;
        }
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
