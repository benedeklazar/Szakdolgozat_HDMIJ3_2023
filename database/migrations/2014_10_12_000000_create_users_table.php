<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->binary('image')->nullable();
            $table->integer('role_id');
            $table->integer('status_id');
        });

        Schema::create('appeal_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('user_id');
            $table->string('text');
            $table->datetime('created_at');
            $table->integer('status_id');
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('user_id');
            $table->integer('status_id');
            $table->integer('visibility_id');
            $table->integer('new_member_status_id')->nullable();
        });

        Schema::create('group_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('user_id');
            $table->integer('role_id');
            $table->integer('status_id');
        });

        Schema::create('objecttypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('user_id');
            $table->string('text');
            $table->binary('image')->nullable();
            $table->integer('status_id');
            $table->integer('visibility_id');
            $table->datetime('created_at');
        });

        Schema::create('removes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('object_type');
            $table->integer('object_id');
            $table->string('reason');
            $table->string('appeal')->nullable();
            $table->tinyInteger('appeal_status');
            $table->datetime('deletion_time')->nullable();
            $table->tinyInteger('delete_mode');
        });

        Schema::create('rights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->string('right_name');
            $table->integer('object_type');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('priority');
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('visibilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        //adatbázis feltöltése default adatokkal:
        DB::statement("
        INSERT INTO `appeal_statuses` (`id`, `name`) VALUES
        (1, '_not_sent'),
        (2, '_sent'),
        (3, '_rejected'),
        (4, NULL)
        ");
        DB::statement("
        INSERT INTO `objecttypes` (`id`, `name`) VALUES
        (1, 'user'),
        (2, 'post'),
        (3, 'group'),
        (4, 'group_user'),
        (5, 'comment')
        ");
        DB::statement("
        INSERT INTO `roles` (`id`, `name`, `priority`) VALUES
        (1, 'admin', 3),
        (2, 'moderator', 2),
        (3, 'member', 1),
        (4, 'guest', 0)
        ");
        DB::statement("
        INSERT INTO `statuses` (`id`, `name`) VALUES
        (1, 'active'),
        (2, 'deleted'),
        (3, 'removed'),
        (4, 'pending'),
        (5, 'invited'),
        (6, 'ban')
        ");
        DB::statement("
        INSERT INTO `visibilities` (`id`, `name`) VALUES
        (1, 'public'),
        (2, 'unlisted'),
        (3, 'private')
        ");
        DB::statement("
        INSERT INTO `rights` (`id`, `role_id`, `right_name`, `object_type`) VALUES
        (1, 1, 'remove', 4),
        (2, 1, 'edit', 4),
        (3, 2, 'remove', 4),
        (4, 1, 'review_appeals', 3),
        (5, 2, 'edit', 4),
        (6, 3, 'create_post', 3),
        (7, 2, 'create_post', 3),
        (8, 1, 'create_post', 3),
        (9, 2, 'remove', 2),
        (10, 1, 'remove', 2),
        (11, 2, 'remove', 5),
        (12, 1, 'remove', 5),
        (13, 1, 'review_hidden', 2),
        (14, 2, 'review_hidden', 2),
        (15, 1, 'invite_member', 3),
        (16, 1, 'list_hidden_members', 3),
        (17, 2, 'list_hidden_members', 3)
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('appeal_statuses');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('group_users');
        Schema::dropIfExists('object_types');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('removes');
        Schema::dropIfExists('rights');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('visibilities');
    }
}
