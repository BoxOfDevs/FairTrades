<?php
namespace BoxOfDevs\FairTrades ; 
use pocketmine\Server;
 use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
 use pocketmine\item\Item;
 use pocketmine\inventory\PlayerInventory;
 
 
class ItemStore {
   public function __construct(Main $pl, Player $player) {
       $this->player = $player;
       $this->line_breaker = C::GOLD . "--------------------------------------\n";
       $this->playercard = [];
       $this->pl = $pl;
       $this->logger = $this->pl->getLogger();
   }
   public function hasItem($itemid, $damage, $counts) {
       $count = 0;
       for ($index = 0; $index < $this->player->getInventory()->getSize(); ++$index) {
           if ($itemid === $player->getInventory()->getItem($index)->getId() and $damage === $player->getInventory()->getItem($index)->getDamage()) {
               $c = $player->getInventory()->getItem($index)->getCount();
               $count = $count + $c;
           }
       }
       if($count <= $counts) {
           return true;
       } else {
           return false;
       }
   }
   public function getAll() {
       $contents = [];
       $itemid = 0;
       while($itemid < 403) {
           if(isset($this->playercard[$itemid])) {
               $id = 0;
               $item = $this->playercard[$itemid];
               while($id < 15) {
                   if(isset($item[$id]) and $this->hasItem($itemid, $id, $item[$id])) {
                       array_push($contents, Item::get($itemid)->getName() . ":" . $id . " x" . $item[$id]);
                       $this->logger->debug("Processing $itemid" . ":$id x$item[$id]");
                   }
                   $id++;
               }
           }
           $itemid++;
       }
       return implode(", ", $contents);
   }
   public function transferItems(Player $player) {
       $itemid = 0;
       while($itemid < 403) {
          if(isset($this->playercard[$itemid])) {
               $id = 0;
               $item = $this->playercard[$itemid];
               while($id < 15) {
                   if(isset($item[$id]) and $this->hasItem($itemid, $id, $item[$id])) {
                       $player->getInventory()->addItem(Item::get($item, $id, $item[$id]));
                       $this->logger->debug($this->player->getName() . " tranfered item $itemid". ":$id x$item[$id] to" . $player->getName());
                       $this->player->getInventory()->remove(Item::get($item, $id, $item[$id]));
                   }
                   $id++;
               }
           }
           $itemid++;
       }
   }
   public function addItem($itemid, $damage, $count) {
           if(isset($this->playercard[$itemid][$damage]) and $this->hasItem($itemid, $damage, $count + $this->playercard[$itemid][$damage])) {
               $this->playercard[$itemid][$damage] = $this->playercard[$itemid][$damage] + $count;
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker .$this->player->getName() ." added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage($this->line_breaker . C::GREEN . "You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->logger->debug($this->player->getName() . " added item $itemid". ":$damage x$count");
           } elseif(isset($this->playercard[$itemid]) and $this->hasItem($itemid, $damage, $count)) {
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker . $this->player->getName() . " added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage($this->line_breaker . C::GREEN . "You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->logger->debug($this->player->getName() . " added item $itemid". ":$damage x$count");
                $this->playercard[$itemid][$damage] =  $count;
           } elseif($this->hasItem($itemid, $damage, $count)) {
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker . $this->player->getName() . " added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage($this->line_breaker . C::GREEN . "You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->logger->debug($this->player->getName() . " added item $itemid". ":$damage x$count");
               $this->playercard[$itemid] = [$damage => $count];
           } else {
               $this->player->sendMessage($this->line_breaker . "Don't try to exange items that you don't have  !");
           }
   }
   public function removeItem($itemid, $damage, $count = "all") {
       if($this->player === $this->player) {
            if(isset($this->playercard[$itemid][$damage])) {
                if($count === "all") {
                    unset($this->playercard[$itemid][$damage]);
                    $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker . $this->player->getName()." remove all " .  Item::get($itemid)->getName() . ":" . $damage);
                    $this->player->sendMessage($this->line_breaker . C::RED . "You removed all " . Item::get($itemid)->getName() . ":" . $damage);
                    $this->logger->debug($this->player->getName() . " added item $itemid". ":$damage x$count");
                } elseif(is_numeric($count)) {
                    $this->playercard[$itemid][$damage] = $this->playercard[$itemid][$damage] - $count;
                    $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker . $this->player->getName()." removed an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                    $this->player->sendMessage($this->line_breaker . C::RED . "You removed an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                   $this->logger->debug($this->player->getName() . " removed item $itemid". ":$damage x$count");
                }
           }
       }
   }
}