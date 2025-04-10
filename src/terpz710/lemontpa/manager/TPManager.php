<?php

declare(strict_types=1);

namespace terpz710\lemontpa\manager;

use pocketmine\player\Player;

use pocketmine\scheduler\ClosureTask;

use pocketmine\utils\SingletonTrait;

use terpz710\lemontpa\LemonTPA;

final class TPManager {
    use SingletonTrait;

    protected array $requests = [];

    public function sendRequest(Player $sender, Player $target, bool $here = false) : void{
        $targetName = $target->getName();
        $this->requests[$targetName] = [
            "from" => $sender->getName(),
            "here" => $here
        ];

        $target->sendMessage("§a{$sender->getName()} has requested to teleport " . ($here ? "you to them" : "to you") . " Use /tpaccept to accept");
        $sender->sendMessage("§aTeleport request sent to {$target->getName()}");

        LemonTPA::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($targetName, $target) {
            if (isset($this->requests[$targetName])) {
                unset($this->requests[$targetName]);
                $target->sendMessage("§cTeleport request expired");
            }
        }), 20 * 30);
    }

    public function hasRequest(Player $target) : bool{
        return isset($this->requests[$target->getName()]);
    }

    public function getRequest(Player $target) : ?array{
        return $this->requests[$target->getName()] ?? null;
    }

    public function acceptRequest(Player $target) : void{
        $request = $this->getRequest($target);

        if ($request === null) {
            $target->sendMessage("§cNo pending teleport requests");
            return;
        }

        $from = Server::getInstance()->getPlayerExact($request["from"]);

        if ($from !== null) {
            if ($request["here"]) {
                $target->teleport($from->getPosition());
                $from->sendMessage("§a{$target->getName()} accepted your teleport request");
                $target->sendMessage("§aTeleporting to {$from->getName()}");
            } else {
                $from->teleport($target->getPosition());
                $from->sendMessage("§aTeleporting to {$target->getName()}");
                $target->sendMessage("§aYou accepted {$from->getName()}'s teleport request");
            }
        } else {
            $target->sendMessage("§cSender is no longer online");
        }

        unset($this->requests[$target->getName()]);
    }
}
