php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryPhoto extends Model { protected $fillable = ['path'];

public function entry()
{
    return $this->belongsTo(TravelEntry::class);
}

} 