php namespace App\Models;

use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model { protected $fillable = [ 'user_id', 'book_id', 'start_date', 'end_date', 'price', 'status', 'type' ];

// Типы аренды
const TWO_WEEKS = '2weeks';
const ONE_MONTH = '1month';
const THREE_MONTHS = '3months';

public static $periods = [
    self::TWO_WEEKS => ['duration' => 14, 'multiplier' => 0.2],
    self::ONE_MONTH => ['duration' => 30, 'multiplier' => 0.4],
    self::THREE_MONTHS => ['duration' => 90, 'multiplier' => 0.9]
];

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function book(): BelongsTo
{
    return $this->belongsTo(Book::class);
}

public function scopeActive($query)
{
    return $query->where('status', 'active')
               ->where('end_date', '>=', now());
}

public function calculatePrice($bookPrice, $type)
{
    return $bookPrice * self::$periods[$type]['multiplier'];
}

public function calculateEndDate($type)
{
    return now()->addDays(self::$periods[$type]['duration']);
}

}