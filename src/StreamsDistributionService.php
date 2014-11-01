<?php namespace Anomaly\Streams\Addon\Distribution\Streams;

use Anomaly\Streams\Addon\Distribution\Streams\Command\GenerateConfigFileCommand;
use Anomaly\Streams\Addon\Distribution\Streams\Command\GenerateDatabaseFileCommand;
use Anomaly\Streams\Addon\Distribution\Streams\Command\InstallApplicationTablesCommand;
use Anomaly\Streams\Addon\Distribution\Streams\Command\InstallModulesCommand;
use Anomaly\Streams\Addon\Distribution\Streams\Command\InstallModulesTableCommand;
use Anomaly\Streams\Addon\Distribution\Streams\Command\InstallRevisionsTableCommand;
use Anomaly\Streams\Addon\Distribution\Streams\Command\InstallStreamsTablesCommand;
use Anomaly\Streams\Addon\Module\Users\User\UserService;
use Anomaly\Streams\Platform\Addon\Module\Command\SyncModulesCommand;
use Anomaly\Streams\Platform\Traits\CommandableTrait;
use Illuminate\Http\Request;

class StreamsDistributionService
{

    use CommandableTrait;

    protected $request;

    protected $user;

    function __construct(Request $request, UserService $user)
    {
        $this->request = $request;
        $this->user    = $user;
    }

    public function install()
    {
        $this->generateConfigFile();
        $this->generateDatabaseFile();

        $this->installApplicationTables();
        $this->installStreamsTables();
        $this->installModulesTable();
        $this->installRevisionsTable();

        $this->syncModules();
        $this->installModules();

        $this->installAdministrator();

        return true;
    }

    private function generateConfigFile()
    {
        $locale   = 'en';
        $timezone = 'UTC';
        $key      = rand_string(30);

        $command = new GenerateConfigFileCommand($key, $locale, $timezone);

        $this->execute($command);
    }

    protected function generateDatabaseFile()
    {
        $input = $this->request->get('database');

        $host     = $input['host'];
        $user     = $input['user'];
        $driver   = $input['driver'];
        $database = $input['database'];
        $password = $input['password'];

        $command = new GenerateDatabaseFileCommand($driver, $host, $database, $user, $password);

        $this->execute($command);
    }

    protected function installApplicationTables()
    {
        $command = new InstallApplicationTablesCommand();

        $this->execute($command);
    }

    protected function installStreamsTables()
    {
        $command = new InstallStreamsTablesCommand();

        $this->execute($command);
    }

    protected function installModulesTable()
    {
        $command = new InstallModulesTableCommand();

        $this->execute($command);
    }

    protected function installRevisionsTable()
    {
        $command = new InstallRevisionsTableCommand();

        $this->execute($command);
    }

    protected function syncModules()
    {
        $command = new SyncModulesCommand();

        $this->execute($command);
    }

    protected function installModules()
    {
        $command = new InstallModulesCommand();

        $this->execute($command);
    }

    protected function installAdministrator()
    {
        $credentials = $this->request->get('administrator');

        $this->user->create($credentials, true);
    }
}
 