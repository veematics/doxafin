public function up()
{
    Schema::create('permission_settings', function (Blueprint $table) {
        $table->id('setting_id');
        $table->foreignId('role_permission_id')
              ->constrained('role_permissions', 'role_permission_id')
              ->onDelete('cascade');
        $table->string('setting_key');
        $table->text('setting_value');
        $table->timestamps();
    });
}