<?php
namespace BoxOfDevs\FairTrades ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
 use pocketmine\Player;


class Main extends PluginBase implements Listener{
public function onEnable(){
$this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->trade-part = [];
$this->trade-with = [];
 }
public function onJoin(PlayerJoinEvent $event){
	$this->trade-part[$event->getPlayer()->getName()] = 0;
}
 public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
switch($cmd->getName()){
	case "trade":
	switch($this->trade-part[$sender->getName()]) {
		case 0:
		if(isset($args[0])) {
			$player2 = $this->getServer()->getPlayer($args[0]);
			$player2->sendMessage("$sender->getName() want to start trading with you. Do /trade accept $sender->getName() to accept the trade or /trade decline $sender->getName() to decline the trade");
			$this->trade-part[$sender->getName()] = 1;
			$this->trade-part[$player2->getName()] = 1;
			$this->trade-with[$sender->getName()] = $player2->getName();
		} else {
			$sender->sendMessage("Usage: /trade <player>");
		}
		break;
		case 1:
		if(isset($args[1])) {
			$player2 = $this->getServer()->getPlayer($args[1]);
			if($this->trade-part[$player2->getName()] === 1 and $this->trade-with[$player2->getName()] === $sender->getName() and $args[0] === "accept") {
				$player2->sendMessage("$sender->getName() accepted your trade! You have 45 seconds to talk together about the trade");
				$this->trade-part[$player2->getName()] = 2;
			    $this->trade-part[$sender->getName()] = 2;
			}
		} else {
			$sender->sendMessage("Usage: /trade accept <player> or /trade decline <player>");
		}
		break;
	}
	return true;
	break;
}
return false;
 }
}