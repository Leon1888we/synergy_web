php namespace App\Http\Controllers;

use App\Models\Book; use App\Models\Author; use App\Models\Category; use Illuminate\Http\Request;

class BookController extends Controller { public function index(Request $request) { $query = Book::with(['author', 'categories'])->available();

    // Фильтрация по категории
    if ($request->has('category')) {
        $query->whereHas('categories', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }
    
    // Фильтрация по автору
    if ($request->has('author')) {
        $query->whereHas('author', function($q) use ($request) {
            $q->where('name', $request->author);
        });
    }
    
    // Фильтрация по году
    if ($request->has('year')) {
        $query->where('year', $request->year);
    }
    
    $books = $query->paginate(12);
    $authors = Author::orderBy('name')->get();
    $categories = Category::orderBy('name')->get();
    
    return view('books.index', compact('books', 'authors', 'categories'));
}

public function show(Book $book)
{
    return view('books.show', compact('book'));
}

}