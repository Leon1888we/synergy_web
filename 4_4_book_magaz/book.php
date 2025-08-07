php namespace App\Models;

use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo; use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model { protected $fillable = [ 'title', 'author_id', 'description', 'price', 'year', 'cover_image', 'is_available', 'stock' ];

public function author(): BelongsTo
{
    return $this->belongsTo(Author::class);
}

public function categories(): BelongsToMany
{
    return $this->belongsToMany(Category::class);
}

public function rentals()
{
    return $this->hasMany(Rental::class);
}

public function scopeAvailable($query)
{
    return $query->where('is_available', true)->where('stock', '>', 0);
}
}