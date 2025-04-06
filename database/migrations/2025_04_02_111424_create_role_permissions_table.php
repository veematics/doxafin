public function up()
{
    Schema::create('role_permissions', function (Blueprint $table) {
        $table->id('role_permission_id');
        $table->foreignId('role_id')->constrained('roles', 'role_id')->onDelete('cascade');
        $table->foreignId('feature_id')->constrained('features', 'feature_id')->onDelete('cascade');
        $table->boolean('can_view')->default(false);
        $table->boolean('can_create')->default(false);
        $table->boolean('can_edit')->default(false);
        $table->boolean('can_delete')->default(false);
        $table->unique(['role_id', 'feature_id']);
        $table->timestamps();
    });
}