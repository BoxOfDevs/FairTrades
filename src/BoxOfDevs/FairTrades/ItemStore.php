<?php
namespace BoxOfDevs\FairTrades ; 
use pocketmine\Server;
 use pocketmine\Player;
 use pocketmine\Item;
 
 
class ItemStore {
   public function __construct(Main $pl, Player $player) {
       $this->player = $player;
       $this->playercard = [];
       $this->pl = $pl;
   }
   public function hasItem(Player $player, $itemid, $damage, $counts) {
       $count = 0;
       foreach ($player->getInventory()->getContents() as $slot => &$inv) {
           if ($itemid === $inv->getId() and $damage === $inv->getDamage()) {
               $c = $inv->getCount();
               $count = $count + $c;
           }
       }
       if($count <= $count) {
           return true;
       } else {
           return false;
       }
   }
   public function transferItems(Player $player) {
       foreach($this->playercard as $item) {
           $id = 0;
           while($id ===! 10) {
               $this->player->getInventory()->addItem(Item::get($itemid, $id, $itemid[$id]));
               $this->player->getInventory()->removeItem(Item::get($itemid, $id, $itemid[$id]));
           }
       }
   }
   public function addItem($itemid, $damage, $count) {
           if(isset($this->playercard[$itemid][$damage]) and hasItem($player, $itemid, $damage, $count + $this->playercard[$itemid][$damage])) {
               $this->playercard[$itemid][$damage] = $this->playercard[$itemid][$damage] + $count;
               $this->pl->trade_with[$player->getName()]->sendMessage("$this->player->getName() added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
               $this->player->sendMessage("You added an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
           } elseif(isset($this->playercard[$itemid])) {
                $this->playercard[$itemid][$damage] =  $count;
           } else {
               $this->playercard[$itemid] = [$damage => $count];
           }
   }
   public function removeItem(Player $player, $itemid, $damage, $count = "all") {
       if($player === $this->player) {
            if(isset($this->playercard[$itemid][$damage])) {
                if($count === "all") {
                    unset($this->playercard[$itemid][$damage]);
                    $this->pl->trade_with[$player->getName()]->sendMessage("$this->player->getName() remove all " .  Item::get($itemid)->getName() . ":" . $damage);
                    $this->player->sendMessage("You removed all " . Item::get($itemid)->getName() . ":" . $damage);
                } elseif(is_numeric($count)) {
                    $this->playercard[$itemid][$damage] = $this->playercard[$itemid][$damage] - $count;
                    $this->pl->trade_with[$player->getName()]->sendMessage("$this->player->getName() removed an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                    $this->player->sendMessage("You removed an item : " . $count . "x " . Item::get($itemid)->getName() . ":" . $damage);
                }
           }
       }
   }
}