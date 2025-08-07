php namespace App\Models;

use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo; use Illuminate\Database\Eloquent\Relations\BelongsToMany; use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model { protected $fillable = [ 'title', 'content', 'is_public', 'is_request_only' ];

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class);
}

public function comments(): HasMany
{
    return $this->hasMany(Comment::class);
}

public function scopePublic($query)
{
    return $query->where('is_public', true);
}

public function scopeWithRequested($query, $requested = true)
{
    return $query->where('is_request_only', $requested);
}

} 