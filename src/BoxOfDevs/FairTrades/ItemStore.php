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
   public function hasItem(Item $item) {
       $count = 0;
       for ($index = 0; $index < $this->player->getInventory()->getSize(); ++$index) {
           if ($item->getId() === $this->player->getInventory()->getItem($index)->getId() and $item->getDamage() === $this->player->getInventory()->getItem($index)->getDamage()) {
               $c = $this->player->getInventory()->getItem($index)->getCount();
               $count = $count + $c;
           }
       }
       if($count >= $item->getCount()) {
           return true;
       } else {
           return false;
       }
   }
   public function getAll() {
       $contents = [];
       foreach($this->playercard as $item) {
                   if($this->hasItem($item)) {
                       array_push($contents, $item->getName() . ":" . $item->getDamage() . " x" . $item->getCount());
                       $this->logger->debug("Processing " . $item->getId() .":" . $item->getDamage() . " x" . $item->getCount());
                   }
       }
       return implode(", ", $contents);
   }
   public function transferItems(Player $player) {
       foreach($this->playercard as $item) {
                   if($this->hasItem($item)) {
                       $player->getInventory()->addItem($item);
                       $this->logger->debug($this->player->getName() . " tranfered item " . $item->getId() . ":" . $item->getDamage() . " x" .  $item->getCount() . " to" . $player->getName());
                       $this->player->getInventory()->remove($item);
                   }
       }
   }
   private function getCount(Item $item) {
       return 0;
       foreach($this->playercard as $storeditem) {
           if($storeditem->getId() === $item->getId() and $storeditem->getDamage() === $item->getDamage()) {
               return $item->getCount();
           }
       }
   }
   private function setCount($count) {
       $id = 0;
       foreach($this->playercard as $storeditem) {
           if($storeditem->getId() === $item->getId() and $storeditem->getDamage() === $item->getDamage() and is_numeric($count)) {
               unset($this->playercard[$id]);
               $storeditem->setCount($count);
               array_push($this->playercard, $storeditem);
           }
           $id++;
       }
   }
   public function isItemSet($item) {
       return false;
       foreach($this->playercard as $storeditem) {
           if($storeditem->getId() === $item->getId() and $storeditem->getDamage() === $item->getDamage()) {
               return true;
           }
       }
   }
   public function addItem(Item $item) {
           if($this->hasItem($item)) {
               $count = $this->getCount($item);
               $item->setCount($count + $item->getCount());
               array_push($this->playercard, $item);
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker .$this->player->getName() ." added an item : " . $item->getCount() . " x" . $item->getName() . ":" . $item->getDamage());
               $this->player->sendMessage($this->line_breaker . C::GREEN . "You added an item : " . $item->getCount() . "x " . $item->getName() . ":" . $item->getDamage());
               $this->logger->debug($this->player->getName() . " added item " . $item->getId() . ":" . $item->getDamage() . " x" . $item->getCount());
           } else {
               $this->player->sendMessage($this->line_breaker . "Don't try to exange items that you don't have  !");
           }
   }
   public function removeItem(Item $item) {
            if($this->isItemSet($item)) {
                if($item->getCount() === 999) {
                    $this->unset_item($item);
                    $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker . $this->player->getName()." remove all " . $item->getName() . ":" . $item->getDamage());
                    $this->player->sendMessage($this->line_breaker . C::RED . "You removed all " . $item->getName() . ":" . $item->getDamage());
                    $this->logger->debug($this->player->getName() . " removed all" . $item->getName() . ":" . $item->getDamage() . " x" . $item->getCount());
                } else {
                    $count = $this->getCount($item);
                    $item->setCount($count - $item->getCount());
                    $this->pl->trade_with[$this->player->getName()]->sendMessage($this->line_breaker . $this->player->getName()." removed an item : " . $item->getCount() . "x " . $item->getName() . ":" . $item->getDamage());
                    $this->player->sendMessage($this->line_breaker . C::RED . "You removed an item : " . $item->getCount() . "x " . $item->getName() . ":" . $item->getDamage());
                    $this->logger->debug($this->player->getName() . " removed item " . $item->getName() . ":" . $item->getDamage() .  "x" . $item->getCount());
                }
           }
       }
}