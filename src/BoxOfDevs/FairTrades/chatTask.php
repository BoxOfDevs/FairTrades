<?php
namespace BoxOfDevs\FairTrades; 

use pocketmine\server;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\ServerScheduler;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\IPlayer;
use pocketmine\math\Vector3;

   class chatTask extends PluginTask  implements Listener{
	private $plugin;
	private $player;
    public function __construct(Plugin $plugin, Player $player){
        parent::__construct($plugin);
		$this->p = $plugin;
        $this->line_breaker = C::GOLD . "--------------------------------------\n";
		$this->player = $player;
	}
   public function onRun($tick) {
	   $this->p->setTradePhase($this->player, 3);
	   $this->player->sendMessage($this->line_breaker . C::GREEN . "Times up! Now you can accept or decline the trade by doing /trade accept or /trade decline" . $this->line_breaker);
	   $this->p->getServer()->getPluginManager()->subscribeToPermission(Server::BROADCAST_CHANNEL_USERS, $this->player);
       if($this->player->isOp()) {
           $this->p->getServer()->getPluginManager()->subscribeToPermission(Server::BROADCAST_CHANNEL_USERS, $this->player);
       }
       }
   }