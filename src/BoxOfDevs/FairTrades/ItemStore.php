<?php
namespace BoxOfDevs\FairTrades ; 
use pocketmine\Server;
 use pocketmine\Player;
 use pocketmine\item\Item;
 use pocketmine\inventory\PlayerInventory;
 
 
class ItemStore {
   public function __construct(Main $pl, Player $player) {
       $this->player = $player;
       $this->playercard = [];
       $this->pl = $pl;
   }
   public function hasItem($itemid, $damage, $counts) {
       $count = 0;
       foreach ($this->player->getInventory()->getContents() as $slot => &$inv) {
           if ($itemid === $inv->getId() and $damage === $inv->getDamage()) {
               $this->player->sendPopup("Hey");
               $c = $inv->getCount();
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
                   if(isset($item[$id])) {
                       array_push($contents, Item::get($itemid)->getName() . ":" . $id . " x" . $item[$id]);
                   }
                   $id++;
               }
           }
           $itemid++;
       }
       return implode(", ", $contents);
   }
   public function transferItems(Player $player) {
       foreach($this->playercard as $item) {
           $id = 0;
           while($id < 15) {
               if(isset($item[$id])) {
                   $player->getInventory()->addItem(Item::get($item, $id, $item[$id]));
                   $this->player->getInventory()->removeItem(Item::get($item, $id, $item[$id]));
               }
               $id++;
           }
       }
   }
   public function addItem($itemid, $damage, $count) {
           if(isset($this->playercard[$itemid][$damage]) and $this->hasItem($itemid, $damage, $count + $this->playercard[$itemid][$damage])) {
               $this->playercard[$itemid][$damage] = $this->playercard[$itemid][$damage] + $count;
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->player->getName() ." added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage("You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
           } elseif(isset($this->playercard[$itemid]) and $this->hasItem($itemid, $damage, $count)) {
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->player->getName() . " added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage("You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                $this->playercard[$itemid][$damage] =  $count;
           } elseif($this->hasItem($itemid, $damage, $count)) {
               $this->pl->trade_with[$this->player->getName()]->sendMessage($this->player->getName() . " added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage("You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->playercard[$itemid] = [$damage => $count];
           } else {
               $this->player->sendMessage("Don't try to exange items that you don't have  !");
           }
   }
   public function removeItem($itemid, $damage, $count = "all") {
       if($this->player === $this->player) {
            if(isset($this->playercard[$itemid][$damage])) {
                if($count === "all") {
                    unset($this->playercard[$itemid][$damage]);
                    $this->pl->trade_with[$this->player->getName()]->sendMessage($this->player->getName()." remove all " .  Item::get($itemid)->getName() . ":" . $damage);
                    $this->player->sendMessage("You removed all " . Item::get($itemid)->getName() . ":" . $damage);
                } elseif(is_numeric($count)) {
                    $this->playercard[$itemid][$damage] = $this->playercard[$itemid][$damage] - $count;
                    $this->pl->trade_with[$this->player->getName()]->sendMessage($this->player->getName()." removed an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                    $this->player->sendMessage("You removed an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                }
           }
       }
   }
}