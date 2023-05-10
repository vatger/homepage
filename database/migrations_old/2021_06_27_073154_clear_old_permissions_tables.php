<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ClearOldPermissionsTables extends Migration
{
    // From config->acl
    // THE CONFIG MIGHT HAVE ALREADY BEEN DELETED
    protected $tables = [
        'groups' => 'membership_groups',
        'permissions' => 'membership_permissions',
        // 'users'                       => 'membership_accounts', // DO NOT DELETE THIS
        'group_has_permissions' => 'membership_group_has_permissions',
        'user_has_permissions' => 'membership_account_has_permissions',
        'user_has_groups' => 'membership_account_has_groups',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $output = new ConsoleOutput();

        $output->writeln('<info>Removing table: ' . $this->tables['group_has_permissions'] . '</info>');
        Schema::dropIfExists($this->tables['group_has_permissions']);

        $output->writeln('<info>Removing table: ' . $this->tables['user_has_permissions'] . '</info>');
        Schema::dropIfExists($this->tables['user_has_permissions']);

        $output->writeln('<info>Removing table: ' . $this->tables['user_has_groups'] . '</info>');
        Schema::dropIfExists($this->tables['user_has_groups']);

        $output->writeln('<info>Removing table: ' . $this->tables['groups'] . '</info>');
        Schema::dropIfExists($this->tables['groups']);

        $output->writeln('<info>Removing table: ' . $this->tables['permissions'] . '</info>');
        Schema::dropIfExists($this->tables['permissions']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // NO WAY BACK HERE
    }
}
