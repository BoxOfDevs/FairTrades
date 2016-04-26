# FairTrades or Trader Joe's (yes, named after that store)

A plugin with an awesome trading system. No economy system required.
The plugin name has not been decided yet, so an issue has been created titled "Plugin name"
Please reply to that issue with your opinion on the plugin name

# Planned Features

Lets say $player1 = TheDragonRing  and   $player2 = remote_vase
$player1 types "/trade $player2"
Once $player1 types that, chat is suspended for those players for a duration of 10 seconds as both players can privately message each other for reason why want trade.
After those 10 seconds, chat resumes for both players, and $player2 can type "/trade accept" or "/trade decline".
If trade is declined $player1 will recieve a message that $player2 declined trade.
If trade is accepted, chat will pause for those 2 players as they trade.
Then $player1 can type "/trade additem <item in $player1 inventory>"
The plugin will check $player1's inventory for if that item is there, and if it is, $player2 will recieve
"$player1 added an item to trade: <item>"
That additem and removeitem cycle will go on until both players type /trade final accept
At any point throughout the trading cycle, a player can type "/trade cancel" to cancel trade.
Obviously, if an item is removed or trade is canceled, the items will return back to the inventory required. 
While chat is suspended, the two players can chat with each other, but with no one else. 

# More detailed description of suspended chat

$player1 can recieve messages from $player2, and $player2 can recieve messages from $player1. 
Neither of them can recieve messages from anyone or anything else, including console, and admins, so as not to disrupt their trading.

Hope you like these plans!
