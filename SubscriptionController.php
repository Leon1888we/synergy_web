php namespace App\Http\Controllers;

use App\Models\User; use Illuminate\Http\Request;

class SubscriptionController extends Controller { public function subscribe(User $user) { if ( 