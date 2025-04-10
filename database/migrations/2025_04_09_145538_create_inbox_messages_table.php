<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inbox_messages', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            // Foreign key for threading replies. Nullable if it's an original message.
            $table->foreignId('message_parent_id')->nullable()->constrained('inbox_messages')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            // Example priority: 1=Low, 2=Normal, 3=High. Adjust as needed.
            $table->tinyInteger('priority_status')->default(2);
            // Foreign key for the sender. Nullable if it's a system message.
            $table->foreignId('sent_from')->nullable()->constrained('users')->onDelete('cascade'); // Assuming your users table is named 'users'
             // Foreign key for the recipient.
            $table->foreignId('sent_to')->constrained('users')->onDelete('cascade'); // Assuming your users table is named 'users'
            $table->timestamps(); // Adds created_at and updated_at
            $table->softDeletes(); // Optional: Adds deleted_at for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_messages');
    }
};