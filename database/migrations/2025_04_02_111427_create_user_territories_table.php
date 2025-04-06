public function up()
{
    Schema::create('user_territories', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('territory_id')
              ->constrained('territories', 'territory_id')
              ->onDelete('cascade');
        $table->primary(['user_id', 'territory_id']);
        $table->timestamps();
    });
}