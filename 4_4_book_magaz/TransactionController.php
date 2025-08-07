php namespace App\Http\Controllers;

use App\Models\Book; use App\Models\Rental; use App\Models\Order; use App\Models\OrderItem; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller { public function rentBook(Request $request, Book $book) { $validated = $request->validate([ 'rental_type' => 'required|in:2weeks,1month,3months' ]);

    // Проверяем доступность
    if (!$book->is_available || $book->stock <= 0) {
        return back()->with('error', 'Книга недоступна для аренды');
    }

    $rental = new Rental();
    $rental->user_id = Auth::id();
    $rental->book_id = $book->id;
    $rental->type = $validated['rental_type'];
    $rental->price = $rental->calculatePrice($book->price, $validated['rental_type']);
    $rental->start_date = now();
    $rental->end_date = $rental->calculateEndDate($validated['rental_type']);
    $rental->status = 'active';
    $rental->save();

    // Уменьшаем количество доступных книг
    $book->decrement('stock');
    if ($book->stock <= 0) {
        $book->update(['is_available' => false]);
    }

    return redirect()->route('user.rentals')->with('success', 'Книга успешно арендована!');
}

public function purchaseBook(Request $request, Book $book)
{
    // Проверяем доступность
    if (!$book->is_available || $book->stock <= 0) {
        return back()->with('error', 'Книга недоступна для покупки');
    }

    // Создаем заказ
    $order = Order::create([
        'user_id' => Auth::id(),
        'total' => $book->price,
        'status' => 'completed'
    ]);

    // Добавляем книгу в заказ
    OrderItem::create([
        'order_id' => $order->id,
        'book_id' => $book->id,
        'quantity' => 1,
        'price' => $book->price
    ]);

    // Уменьшаем количество доступных книг
    $book->decrement('stock');
    if ($book->stock <= 0) {
        $book->update(['is_available' => false]);
    }

    return redirect()->route('user.orders')->with('success', 'Книга успешно куплена!');
}

}