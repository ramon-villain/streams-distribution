<?php namespace Anomaly\Streams\Distribution\Base\Http\Controller;

use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Anomaly\Streams\Distribution\Base\BaseDistributionService;

class InstallController extends PublicController
{
    /**
     * Install the system.
     *
     * @param BaseDistributionService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function install(BaseDistributionService $service)
    {
        if ($service->install()) {

            return redirect('installer/complete');

        }
    }

    /**
     * Show the welcome page.
     *
     * @return mixed
     */
    public function complete()
    {
        return view('distribution.base::complete');
    }
}