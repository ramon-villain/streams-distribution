<?php namespace Anomaly\Streams\Addon\Distribution\Streams;

use Illuminate\Support\ServiceProvider;

/**
 * Class StreamsDistributionServiceProvider
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Addon\Distribution\Streams
 */
class StreamsDistributionServiceProvider extends ServiceProvider
{
    /**
     * Register the service.
     */
    public function register()
    {
        $this->checkInstallation();
        $this->registerRoutes();
    }

    /**
     * If we're not in the installer and the distribution
     * file is not present than Streams is considered to
     * be NOT installed.
     *
     * In the case that Streams is not installed route
     * EVERYTHING to the installer.
     */
    protected function checkInstallation()
    {
        if (app('request')->segment(1) !== 'installer' && !file_exists(base_path('config/distribution.php'))) {

            app('router')->any(
                '{all}',
                function () {
                    return redirect(url('installer'));
                }
            )->where('all', '.*');

            return;
        }
    }

    /**
     * Register distribution routes.
     */
    protected function registerRoutes()
    {
        $this->app->register('Anomaly\Streams\Addon\Distribution\Streams\Provider\RouteServiceProvider');
    }
}
