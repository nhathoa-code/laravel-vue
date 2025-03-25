<?php

namespace Database\Seeders;

use App\Models\Administration\AuthorizedCategory;
use App\Models\Book\Book;
use App\Models\Book\BookItem;
use App\Models\Administration\AuthorizedValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::query()->delete();
        Storage::disk('public')->deleteDirectory('images');
        $items = Http::get('https://www.googleapis.com/books/v1/volumes',[
                'q' => 'subject:fiction',
                'maxResults' => 1,
                'orderBy' => 'newest'
        ])->json()['items'];
        foreach($items as $i){
            $book = $i['volumeInfo'];
            $isbn = isset($book['industryIdentifiers']) ? ($book['industryIdentifiers'][0]['type'] == 'ISBN_13' ? $book['industryIdentifiers'][0]['identifier'] : fake()->isbn13()) : fake()->isbn13();
            $coverImageContent = file_get_contents($book['imageLinks']['thumbnail']);
            $coverImagePath = 'images/' . now()->format('Y/m') . '/' . Str::slug($book['title']) . '.jpg';
            Storage::disk('public')->put($coverImagePath,$coverImageContent);
            $authorizedCategory = AuthorizedCategory::where('code','LOC')->first();
            $value = AuthorizedValue::where('category_id',$authorizedCategory->id)->inRandomOrder()->first();
            Book::factory()
                ->has(BookItem::factory()
                    ->state([
                        'shelving_location' => $value->id,
                    ])
                    ->count(10),'items')
                ->create([
                    'isbn' => $isbn,
                    'title' => $book['title'],
                    'authors' => $book['authors'],
                    'publisher' => $book['publisher'],
                    'published_date' => $book['publishedDate'],
                    'description' => $book['description'],
                    'page_count' => $book['pageCount'],
                    'cover_image' => $coverImagePath,
                    'language' => $book['language']
                ]);
        }
    }
}
