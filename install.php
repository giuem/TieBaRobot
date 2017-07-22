<?php

require __DIR__ . '/bootstrap/app.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Ramsey\Uuid\Uuid;

Capsule::schema()->dropIfExists('manager');
Capsule::schema()->create('manager', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('account');
    $table->string('password');
});

Capsule::schema()->dropIfExists('bots');
Capsule::schema()->create('bots', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->text('bduss');
    $table->string('last_pid')->default('0');
    $table->string('type', 8)->default('master');
});

Capsule::schema()->dropIfExists('settings');
Capsule::schema()->create('settings', function (Blueprint $table) {
    $table->increments('id');
    $table->string('key');
    $table->text('value')->nullable();
    $table->string('belong_to')->index();
});

Capsule::schema()->dropIfExists('logs');
Capsule::schema()->create('logs', function (Blueprint $table) {
    $table->increments('id');
    $table->text('msg');
    $table->timestamps();
});





Capsule::table('manager')->insert([
    'id' => Uuid::uuid4()->toString(),
    'account' => 'admin',
    'password' => password_hash('admin', PASSWORD_DEFAULT)
]);




echo "done\n";