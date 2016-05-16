<?php
namespace BoxOfDevs\FairTrades ; 
use pocketmine\Server;
 use pocketmine\Player;
 use pocketmine\Item;
class item_exange {
   public function __construct(Player $player1, Player $player2) {
       $this->player1 = $player1;
       $this->player2 = $player2;
       $this->player1card = [];
       $this->player2card = [];
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
   public function addItem(Player $player, $itemid, $damage, $count) {
       if($player === $this->player1 and hasItem($player, $itemid, $damage, $count)) {
           if(isset($this->player1card[$itemid][$damage])) {
               $this->player1card[$itemid][$damage] = $this->player1card[$itemid][$damage] + $count;
           } elseif(isset($this->player1card[$itemid])) {
                $this->player1card[$itemid][$damage] =  $count;
           } else {
               $this->player1card[$itemid] = [$damage => $count];
           }
       } elseif($player === $this->player2 and hasItem($player, $itemid, $damage, $count)) {
           if(isset($this->player2card[$itemid][$damage])) {
               $this->player2card[$itemid][$damage] = $this->player1card[$itemid][$damage] + $count;
           } elseif(isset($this->player2card[$itemid])) {
                $this->player2card[$itemid][$damage] =  $count;
           } else {
               $this->player2card[$itemid] = [$damage => $count];
           }
       }
   }
   public function removeItem(Player $player, $itemid, $damage, $count = "all") {
       if($player === $this->player1) {
            if(isset($this->player1card[$itemid][$damage])) {
                if($count === "all") {
                    unset($this->player1card[$itemid][$damage]);
                } elseif(is_numeric($count)) {
                    $this->player1card[$itemid][$damage] = $this->player1card[$itemid][$damage] - $count;
                }
           }
       } elseif($player === $this->player2) {
           if(isset($this->player2card[$itemid][$damage])) {
                if($count === "all") {
                    unset($this->player2card[$itemid][$damage]);
                } elseif(is_numeric($count)) {
                    $this->player2card[$itemid][$damage] = $this->player2card[$itemid][$damage] - $count;
                }
           }
       }
   }
}