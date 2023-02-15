import sys
import os
from stockfish import Stockfish
stockfish = Stockfish(path="../app/executable/stockfish.exe")

#sys.argv[1] contains the first argument provided which will be the username of 
#the player who is playing against the pc

fileName = sys.argv[1]

#sys.argv[2] contains the fen string representing the position where stockfish
#need to do the best move

fen = sys.argv[2]

skill = sys.argv[3]

time = int(skill)



moveOverhead = 0


if time == 0:
    time = 1

file = open(f"../app/generated_files/{fileName}", "w")

stockfish.update_engine_parameters({"Move Overhead": moveOverhead,"UCI_LimitStrength": "true"})

stockfish.set_skill_level(skill)



stockfish.set_fen_position(fen)

stockfish.make_moves_from_current_position([stockfish.get_best_move_time(time)])

file.write(stockfish.get_fen_position())

file.close()