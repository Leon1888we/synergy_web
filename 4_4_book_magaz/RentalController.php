php namespace App\Http\Controllers;

use App\Models\Book; use App\Models\Rental; use Illuminate\Http\Request; use Illuminate\Support\Carbon;

class RentalController extends Controller { public function rent(Book $book, Request $request) { $validated = $request->validate([ 'period' => 'required|in:2weeks,1month,3months' ]);

    $periodMap = [
        '2weeks' => ['price' => $book->price * 0.2, 'days' => 14],
        '1month' => ['price' => $book->price * 0.4, 'days' => 30],
        '3months' => ['price' => $book->price * 0.9, 'days' => 90]
    ];
    
    $rental = Rental::create([
        'user_id' => auth()->id(),
        'book_id' => $book->id,
        'start_date' => now(),
        'end_date' => now()->addDays($periodMap[$validated['period']]['days']),
        'price' => $periodMap[$validated['period']]['price'],
        'status' => 'active'
    ]);
    
    $book->decrement('stock');
    
    return redirect()->route('rentals.index')->with('success', 'Book rented successfully!');
}

}