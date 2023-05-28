# OscarChessPlatform
- [What is it?](#what-is-it)
- [How does it work?](#how-does-it-work)
  * [Against computer](#against-computer)
  * [Against player](#against-player)
  * [Learn secton](#learn-section)
    + [Puzzle](#puzzle)
 
  * [Watch another player](#watch-another-player)
  
- [Credits](#credits)



## What is it?
It's a web application that allow you to do different things.\
For now you can play against the computer and another player.




## How does it work?

### Against computer

  When the user land on the vsComputer page it will connect to a webSocket server and every time\
  he makes a move a message is sent indicating the request(VsComputer), the current fen position, the skill of the\     
  computer the player chose, the username (which is a uniqueId) and a jwt. \
  On the server side when the server receive a vsComputer request thanks to stockfish-py \
  it uses the stockfish engine to calculate the best move according to the skill provided\
  then the move is saved in a .txt file with the name of the username which will be unique for everyone.\
  The server will be then be able to read the content of the file, get the best move, and send it back\
  to who sent the message. The file then gets deleted. 
  The idea of the jwt token sent is to save games in the database if the player is logged in \
  but this needs to be implemented.

### Against player

  As for the versus computer, the vsPlayer uses web socket to send and receive information\
  about the game. The logic of the game is based on a switch case. That's because\
  for example when the user first lands on the page a request vsPlayer is sent with a state of\
  newGame: the server will check if the user is already in an existing match and if he is it will sends\
  all the information he needs to continue playing the game. If the server doesn't found any game\
  he was playing in he will check if there are other player waiting for a second player to join. If there\
  are it will join that game and start the match, if there aren't it will create a game with a status of \
  waiting another player. When another user will look for a game and find the game created it will notify \
  the user who created the game and start the match.\
  Every time a player makes a move it will send an update state changing the PGN and the last move and will send\
  a message to the other player containing the PGN and the move he played.\
  When a game is over a finsh message is sent and there could be some scenarios. Since there is no timer\
  if the player send a message finish it has either draw or win since you can't lose on your turn.\
  A msg is sent along with the state finish indicating if the player has won or draw. In either case it will\
  notify the other user of the state of the game. 

### Learn section

#### Puzzle
  Uses lichess.org api to get the information to display the puzzle by sending a get\
  request. Example https://lichess.org/api/puzzle/00008. The ids are stored in the database\
  with the elo which rapresent the dificulty and keywords that defines the category.

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
| Php Ratchet | https://github.com/ratchetphp | https://github.com/ratchetphp/Ratchet |
| Google auth | https://github.com/googleapis | https://github.com/googleapis/google-auth-library-php |
