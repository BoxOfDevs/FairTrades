<?php
namespace BoxOfDevs\FairTrades ; 
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use BoxOfDevs\FairTrades\chatTask;
use BoxOfDevs\FairTrades\item_exange;
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
public function setTradePhase(Player $player, $tradephase) {
	if(!is_numeric($tradephase)) {
		return false;
	} else {
		$this->trade-part[$player->getName()] = $tradephase;
		return true;
	}
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
			if($this->trade-part[$player2->getName()] === 1 and $this->trade-with[$player2->getName()] === $sender->getName() and $args[0] === "accept") { //If the trade is accepted
				$player2->sendMessage("$sender->getName() accepted your trade! You have 45 seconds to talk together about the trade");
				$this->trade-part[$player2->getName()] = 2;
			    $this->trade-part[$sender->getName()] = 2;
				$this->getServer()->getScheduler()->scheduleDelayedTask(new  chatTask($this, $sender), 900); //shedule task so in 45 seconds, they will be switched to part 3;
				$this->getServer()->getScheduler()->scheduleDelayedTask(new  chatTask($this, $player2), 900);
				$this->getServer()->getPluginManager()->unsubscribeFromPermission(Server::BROADCAST_CHANNEL_USERS, $sender);
				$this->getServer()->getPluginManager()->unsubscribeFromPermission(Server::BROADCAST_CHANNEL_USERS, $player2);
				$this->trade-with[$sender->getName()] = $player2->getName();
				
			} elseif($this->trade-part[$player2->getName()] === 1 and $this->trade-with[$player2->getName()] === $sender->getName() and $args[0] === "decline") {
				$player2->sendMessage("$sender->getName() declined your trade.");
				$this->trade-part[$player2->getName()] = 0;
			    $this->trade-part[$sender->getName()] = 0;
				unset($this->trade-with[$player2->getName()]);
			} elseif($this->trade-part[$player2->getName()] === 1 and $this->trade-with[$player2->getName()] === $sender->getName()) {
				$sender->sendMessage("Please enter a correct choice: /trade accept <player> or /trade decline <player>");
			} elseif(!$this->trade-part[$player2->getName()] === 1 or !$this->trade-with[$player2->getName()] === $sender->getName()) {
				$sender->sendMessage("$player2->getName() is not trading with you");
			}
		} else {
			$sender->sendMessage("Usage: /trade accept <player> or /trade decline <player>");
		}
		break;
		case 3:
		case 3.5:
		if(isset($args[0])) {
			$player2 = $this->trade-with[$sender->getName()];
			if($args[0] === "accept") { //If the trade is accepted
				$player2->sendMessage("$sender->getName() accepted the trade.");
				$this->trade-part[$sender->getName()] = 3.5;
				if($this->trade-part[$player2->getName() === 3.5]) { // if the other player already accepted the trade, they would have both accepted
				      $this->trade-part[$sender->getName()] = 4;
					  $this->trade-part[$player2->getName()] = 4;
				}
				$this->trade-part[$player2->getName()] = 2;
			    $this->trade-part[$sender->getName()] = 2;
			} elseif($args[0] === "decline") {
				$player2->sendMessage("$sender->getName() declined the trade.");
				$this->trade-part[$player2->getName()] = 0;
			    $this->trade-part[$sender->getName()] = 0;
				unset($this->trade-with[$player2->getName()]);
				unset($this->trade-with[$sender->getName()]);
			} else {
				$sender->sendMessage("Please enter a correct choice: /trade accept or /trade decline");
			}
		} else {
			$sender->sendMessage("Usage: /trade accept or /trade decline");
		}
		break;
		case 4:
		if(isset($args[2])) {
			$player2 = $this->trade-with[$sender->getName()];
			if($args[0] === "additem") { //If the trade is accepted
				$player2->sendMessage("$sender->getName() added an item.");
				$item = explode(":", $args[1]);
				$this->trade-part[$player2->getName()] = 2;
			    $this->trade-part[$sender->getName()] = 2;
			} elseif($args[0] === "removeitem") {
				$player2->sendMessage("$sender->getName() declined the trade.");
				$this->trade-part[$player2->getName()] = 0;
			    $this->trade-part[$sender->getName()] = 0;
				unset($this->trade-with[$player2->getName()]);
				unset($this->trade-with[$sender->getName()]);
			} else {
				$sender->sendMessage("Please enter a correct choice: /trade accept or /trade decline");
			}
		} else {
			$sender->sendMessage("Usage: /trade accept or /trade decline");
		}
		break;
	}
	return true;
	break;
}
return false;
 }
}