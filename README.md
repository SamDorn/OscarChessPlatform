# OscarChessPlatform
- [What is it?](#what-is-it)
- [How does it work?](#how-does-it-work)
  * [Against computer](#against-computer)
  * [Against player](#against-player)
  * [Learn secton](#laern-section)
  * [Watch another player](#watch-another-player)
  
- [Credits](#credits)



## What is it?
It's a web application that allow you to do different things.\
For now you can play against a computer which is set at the minimum level.\
When it will be finished you should be able to:\
-Play against the computer at differents levels;\
-Play against another player (only if sign-in);\
-Watch the games you played to learn from your mistakes (only if sign-in);\
-Learn from a repository of chess basics, patterns and more;\
-Watch another player's game in live. 




## How does it work?

### Against computer

  Every time the player makes a move, an ajax request, sending the current fen, the session_id()\
  and the skill level of the computer, is sent to the index.php which \
  redirect the request to the app/controllers/AjaxController. The AjaxController runs the app/python/main.py script. The script uses the stockfish.py\
  library which allows the usage of the stockfish engine more easily.\
  The script will set the engine to the skill provided, will set the fen position provided\
  and will calculate the best move according to the skill level. The fen representing the move\
  stockfish played is saved in a file named with the session_id provided.\
  The AjaxController will then read the file created, echo out the fen and the board is updated. 

### Against player

  It uses webSocket to connect two player. If there are zero games with no second \
  opponents the server will create a new game. Every time a player makes a move \
  a message is sent to the server with the new position of the board and the server \
  will then send a message to the other player to update the chessboard and the game.

### Learn section

  To define

### Watch another player

  To define

  
## Credits

| Name              | Author                |Link                                                                  |
| ---------------   | --------------------- | ---------------------------- |
| Chess.js| https://github.com/jhlywa|https://github.com/jhlywa/chess.js |
| cm-chessboard | https://github.com/shaack/ | https://github.com/shaack/cm-chessboard |
| Stockfish.py | https://github.com/zhelyabuzhsky | https://github.com/zhelyabuzhsky/stockfish |
| Stockfish 15 | https://github.com/official-stockfish | https://github.com/official-stockfish/Stockfish |
| jQuery | https://github.com/jquery | https://github.com/jquery/jquery |
