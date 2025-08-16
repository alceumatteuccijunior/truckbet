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
            Schema::table('users', function (Blueprint $table) {
                // Coluna para armazenar o código de recuperação
                $table->string('password_reset_code')->nullable()->after('password');
                // Coluna para armazenar a data de expiração do código
                $table->timestamp('password_reset_expires_at')->nullable()->after('password_reset_code');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('password_reset_code');
                $table->dropColumn('password_reset_expires_at');
            });
        }
    };
    