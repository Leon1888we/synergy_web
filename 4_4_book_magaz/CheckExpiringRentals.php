## Уведомления о сроке аренды
app/Console/Commands/CheckExpiringRentals.php:

php namespace App\Console\Commands;

use App\Models\Rental; use App\Notifications\RentalExpiringNotification; use Illuminate\Console\Command;

class CheckExpiringRentals extends Command { protected $signature = 'rentals:check-expiring'; protected $description = 'Check for expiring rentals and send notifications';

public function handle()
{
    $rentals = Rental::where('end_date', '<=', now()->addDays(3))
                    ->where('status', 'active')
                    ->get();

    foreach ($rentals as $rental) {
        $rental->user->notify(new RentalExpiringNotification($rental));
    }

    $this->info('Sent notifications for ' . $rentals->count() . ' expiring rentals.');
}

}