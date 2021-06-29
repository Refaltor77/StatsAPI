<?php

namespace refaltor\stats;

use pocketmine\plugin\PluginBase;
use refaltor\stats\Provider\MySqlProvider;
use refaltor\stats\Provider\ProviderInterface;
use refaltor\stats\Provider\SQliteProvider;

class Main extends PluginBase
{
    private static self $instance;
    public ProviderInterface $provider;

    public static function getInstance(): self{
    return self::$instance;
    }

    public function onEnable(){
    self::$instance = $this;
    $this->saveResource('config.yml');
    $provider = $this->getConfig()->get('provider');
        switch ($provider){
            case 'sqlite':
                $this->provider = new SQliteProvider();
                $this->provider->prepare();
                break;
            case 'mysql':
                $this->provider = new MySqlProvider();
                $this->provider->prepare();
                break;
        }
    }

    /**
     * Allows to have the API of the plugin
     * @return ProviderInterface
     */
    public function getStatsAPI(): ProviderInterface{
        return $this->provider;
    }
}