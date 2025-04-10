<?php

declare(strict_types=1);

namespace terpz710\lemontpa;

use pocketmine\plugin\PluginBase;

use terpz710\lemontpa\commands\TPACommand;
use terpz710\lemontpa\commands\TPAHereCommand;
use terpz710\lemontpa\commands\TPAcceptCommand;

use CortexPE\Commando\PacketHooker;
use CortexPE\Commando\BaseCommand;

use DaPigGuy\libPiggyUpdateChecker\libPiggyUpdateChecker;

class LemonTPA extends PluginBase {

    protected static self $instance;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->checkVirions();
        
        libPiggyUpdateChecker::init($this);

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->registerAll("LemonTPA", [
            new TPACommand($this, "tpa", "request a teleport"),
            new TPAHereCommand($this, "tpahere", "teleport someone to your location"),
            new TPAcceptCommand($this, "tpaccept", "accept a teleport request")
        ]);
    }

    public static function getInstance() : self{
        return self::$instance;
    }

    private function checkVirions() : void{
        foreach (
            [
                "Commando" => BaseCommand::class,
                "libPiggyUpdateChecker" => libPiggyUpdateChecker::class
            ] as $virion => $class
        ) {
            if (!class_exists($class)) {
                $this->getLogger()->error($virion . " virion not found. Download LemonTPA at https://poggit.pmmp.io/ci/Terpz710/LemonTPA/LemonTPA");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }
    }
}
