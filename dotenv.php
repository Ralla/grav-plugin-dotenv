<?php
namespace Grav\Plugin;

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

    public function onPluginsInitialized()
    {
        $this->dotenv = new Dotenv(GRAV_ROOT, '.gravenv');

        try {
            $this->init();
        } catch (\Exception $e) {
            $this->grav['debugger']->addMessage('DotEnv: ' . $e->getMessage());
        }
    }

    protected function init()
    {
        foreach ($this->dotenv->load() as $setting) {
            $settingData = $this->getSetting($setting);

            $this->grav['config']->join($settingData['grav_pointer'], [
                $settingData['key'] => getenv($settingData['env_name']),
            ]);
        }
    }

    protected function getSetting($setting)
    {
        if (strpos($setting, '=') !== false) {
            list($name, $value) = array_map('trim', explode('=', $setting, 2));
        }

        // Name is not dot notated, it has to be.
        if (strpos($name, '.') === false) {
            return;
        }

        $parts = explode('.', $name);

        return [
            'grav_pointer'  => implode('.', array_slice($parts, 0, -1)),
            'env_name'      => implode('.', $parts),
            'key'           => end($parts),
            'value'         => $value,
        ];
    }
}
