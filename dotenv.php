<?php
namespace Grav\Plugin;

require __DIR__ . '/vendor/autoload.php';

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Dotenv\Dotenv;

class DotenvPlugin extends Plugin
{
    /**
     * DotEnv
     *
     * @var dotenv
     */
    private $dotenv;

    /**
     * Subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    /**
     * Initialize hook.
     */
    public function onPluginsInitialized()
    {
        $filename = $this->grav['config']->get('plugins.dotenv.filename');
        $this->dotenv = new Dotenv(GRAV_ROOT, $filename ?? '.gravenv');

        try {
            $this->init();
        } catch (\Exception $exception) {
            $message = 'DotEnv: ' . $exception->getMessage();

            $this->grav['debugger']->addMessage($message);
            $this->grav['log']->warning($message);

            if ($this->isAdmin()) {
                $this->grav['admin']->setMessage($message, 'warning');
            }
        }
    }

    /**
     * Init all environment settings from .gravenv
     */
    protected function init()
    {
        $this->dotenv->load();
        foreach ($this->dotenv->getEnvironmentVariableNames() as $setting) {
            $settingData = $this->getSetting($setting);

            $this->grav['config']->join($settingData['grav_pointer'], [
                $settingData['key'] => getenv($settingData['env_name']),
            ]);
        }
    }

    /**
     * Format setting from .gravenv
     *
     * @param  string   $setting
     *
     * @return array
     */
    protected function getSetting($name)
    {
        // Name is not dot notated, it should be.
        if (strpos($name, '.') === false) {
            return;
        }

        $parts = explode('.', $name);

        return [
            'grav_pointer'  => implode('.', array_slice($parts, 0, -1)),
            'env_name'      => implode('.', $parts),
            'key'           => end($parts),
            'value'         => getenv($name),
        ];
    }
}
