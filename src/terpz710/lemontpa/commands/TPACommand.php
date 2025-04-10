<?php

declare(strict_types=1);

namespace terpz710\lemontpa\commands;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use terpz710\lemontpa\manager\TPManager;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\RawStringArgument;

class TPACommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("lemontpa.tpa");

        $this->registerArgument(0, new RawStringArgument("player"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Run this command in-game!");
            return;
        }

        $target = $args["player"] ?? null;

        if (!$target instanceof Player || $target->getName() === $sender->getName()) {
            $sender->sendMessage("§cInvalid target!");
            return;
        }

        TPManager::getInstance()->sendRequest($sender, $target, false);
    }
}