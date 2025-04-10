<?php

declare(strict_types=1);

namespace terpz710\lemontpa\commands;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use terpz710\lemontpa\manager\TPManager;

use CortexPE\Commando\BaseCommand;

class TPAcceptCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("lemontpa.tpaccept")
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("Run this command in-game!");
            return;
        }

        TPManager::getInstance()->acceptRequest($sender);
    }
}