<?php namespace Anomaly\Streams\Addon\Distribution\Streams\Command;

use Illuminate\Database\Schema\Blueprint;

class InstallApplicationTablesCommandHandler
{
    protected $db;

    protected $schema;

    function __construct()
    {
        $this->db     = app('db');
        $this->schema = app('db')->connection()->getSchemaBuilder();
    }

    public function handle(InstallApplicationTablesCommand $command)
    {
        $this->setPrefix(null);

        $this->installApplicationsTable();
        $this->installApplicationsDomainsTable();

        $this->installDefaultApplication();

        // TODO: Get this from the request data
        $this->setPrefix('default_');
    }

    protected function installApplicationsTable()
    {
        $this->schema->dropIfExists('applications');

        $this->schema->create(
            'applications',
            function (Blueprint $table) {

                $table->increments('id');
                $table->string('name');
                $table->string('reference');
                $table->string('domain');
                $table->string('is_enabled');

            }
        );
    }

    protected function installApplicationsDomainsTable()
    {
        $this->schema->dropIfExists('applications_domains');

        $this->schema->create(
            'applications_domains',
            function (Blueprint $table) {

                $table->increments('id');
                $table->integer('application_id');
                $table->string('domain');
                $table->string('locale');

            }
        );
    }

    protected function installDefaultApplication()
    {
        // TODO: Get this from request input.
        $data = [
            'name'       => 'Default',
            'reference'  => 'defualt',
            'domain'     => 'streams.app',
            'is_enabled' => true,
        ];

        $this->db->table('applications')->insert($data);
    }

    protected function setPrefix($prefix)
    {
        $this->schema->getConnection()->getSchemaGrammar()->setTablePrefix($prefix);
        $this->schema->getConnection()->setTablePrefix($prefix);
    }
}
 