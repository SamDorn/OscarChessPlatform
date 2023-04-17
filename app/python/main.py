import sys
from stockfish import Stockfish

stockfish = Stockfish(path="../executable/stockfish.exe")

#sys.argv[1] contains the first argument provided which will be the username of 
#the player who is playing against the pc

fileName = sys.argv[1]

#sys.argv[2] contains the fen string representing the position where stockfish
#need to do the best move

fen = sys.argv[2]

print(fen)

#sys.argv[3] contains the the skill level which need to be parsed

skill = int(sys.argv[3])





if skill < 10:
    moveOverhead = 0
    time = 1
else:
    moveOverhead = skill
    time = skill


#Creates a new file 

file = open(f"../generated_files/{fileName}", "w")

#Update the stockfish parameters and set the skill and depht

stockfish.update_engine_parameters({"Move Overhead": moveOverhead,"UCI_LimitStrength": "true"})
stockfish.set_skill_level(skill)
stockfish.set_depth(skill)


#Set the board with the fen

stockfish.set_fen_position(fen)

#Calculate the best move and write it in the file

file.write(stockfish.get_best_move_time(time))
file.close()
