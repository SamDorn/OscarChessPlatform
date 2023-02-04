# OscarChessPlatform
- [Roadmap](#roadmap-)
- [What is it?](#what-is-it-)
- [How does it work?](#how-does-it-work-)
  * [Against computer](#against-computer)
  * [Against player](#against-player-)
  * [Learn secton](#laern-section-)
  * [Watch another player](#watch-another-player-)
  
- [Credits](#credits)

## Roadmap
- [x] Play against computer
- [ ] Select level computer
- [ ] Login/signup with database 
- [ ] Play against another player
- [ ] Live chat during online matches
- [ ] Learn section
- [ ] Watch section
- [ ] Gui
- [ ] Watch games played
- [ ] Create api 
- [ ] Solution for files created (player vs PC)

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
  and the skill level of the computer, is sent to the ajax.php script \
  which will execute the python script python/main.py. The script uses the stockfish.py\
  library which allows the usage of the stockfish engine more easily.\
  The script will set the engine to the skill provided, will set the fen position provided\
  and will calculate the best move according to the skill level. The fen representing the move\
  stockfish played is saved in a file called with the session_id provided.\
  The ajax.php will then read the file created, echo out the fen and the board is updated. 

### Against player

  To define

### Learn section

  To define

### Watch another player

  To define

  
## Credits

| Name              | Author                |Link                                                                  |
| ---------------   | --------------------- | ---------------------------- |
| Chess.js| https://github.com/jhlywa|https://github.com/jhlywa/chess.js |
| Chessboard.js | https://chrisoakman.com/ | https://chessboardjs.com/ |
| Stockfish.py | https://github.com/zhelyabuzhsky | https://github.com/zhelyabuzhsky/stockfish |
| Stockfish 15 | https://github.com/official-stockfish | https://github.com/official-stockfish/Stockfish |
| Stockfish 15 | https://github.com/jquery | https://github.com/jquery/jquery |

