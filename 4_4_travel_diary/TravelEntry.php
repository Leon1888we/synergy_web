php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelEntry extends Model { protected $casts = [ 'heritage_sites' => 'array', 'places_to_visit' => 'array', 'start_date' => 'date', 'end_date' => 'date', ];

public function user()
{
    return $this->belongsTo(User::class);
}

public function photos()
{
    return $this->hasMany(EntryPhoto::class);
}

} 