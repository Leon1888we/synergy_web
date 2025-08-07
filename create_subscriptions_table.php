php Schema::create('subscriptions', function (Blueprint $table) { $table->id(); $table->foreignId('subscriber_id')->constrained('users')->onDelete('cascade'); $table->foreignId('target_id')->constrained('users')->onDelete('cascade'); $table->timestamps();
$table->unique(['subscriber_id', 'target_id']);
});
