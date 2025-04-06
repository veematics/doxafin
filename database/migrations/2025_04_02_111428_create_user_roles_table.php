public function up()
{
    Schema::create('user_roles', function (Blueprint $table) {
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('role_id')
              ->constrained('roles', 'role_id')
              ->onDelete('cascade');
        $table->primary(['user_id', 'role_id']);
        $table->timestamps();
    });
}