import sys
from stockfish import Stockfish
stockfish = Stockfish(path="./stockfish/stockfish.exe")

#sys.argv[1] contains the first argument provided which will be the username of 
#the player who is playing against the pc 
#fileName= sys.argv[1]
#sys.argv[2] contains the fen string representing the position where stockfish
#need to do the best move

fileName = sys.argv[1]

fen = sys.argv[2]

skill = sys.argv[3]

file = open(f"./games/{fileName}", "w")

stockfish.set_skill_level(skill)



stockfish.set_fen_position(fen)

stockfish.make_moves_from_current_position([stockfish.get_best_move()])

file.write(stockfish.get_fen_position())

file.close()