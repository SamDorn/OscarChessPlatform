import { INPUT_EVENT_TYPE, COLOR } from "./../../libraries/cm-chessboard/Chessboard.js"
import { MARKER_TYPE } from "./../../libraries/cm-chessboard/extensions/markers/Markers.js"
import { ARROW_TYPE, } from "./../../libraries/cm-chessboard/extensions/arrows/Arrows.js"

import { initializeChessboard, chess, board, drawArraws, getPuzzle, movesPc,movesPlayer, getPuzzleByCategory} from "./game.js";


initializeChessboard()

var result = null //stores the object move. Can be null if the move is invalid
var color = null //color that determine if the user is white or black
var counter = 0

//function that calls the api to get the daily puzzles
getPuzzle("daily", color, inputHandler)

//enable the move input calling the input handler and the color
//so that the user can only pick up pieces from its color
board.enableMoveInput(inputHandler, color)


/* Adding an arrow to the board. */
drawArraws()

getPuzzleByCategory("opening", color, inputHandler)
getPuzzleByCategory("middlegame",color, inputHandler)
getPuzzleByCategory("endgame",color, inputHandler)

/**
 * Handles the input of the user
 * @param {*} event 
 * @returns 
 */
function inputHandler(event) {
    event.chessboard.removeMarkers(MARKER_TYPE.dot) //removes every dot that was generated

    color = chess.turn()
    //case where the user has started to drag or has clicked a piece
    if (event.type === INPUT_EVENT_TYPE.moveInputStarted) {
        const moves = chess.moves({ square: event.square, verbose: true }); //gets all the possible moves from that particular square

        //draw dots on possible squares
        for (const move of moves) {
            event.chessboard.addMarker(MARKER_TYPE.dot, move.to)
        }
        return moves.length > 0

    } else if (event.type === INPUT_EVENT_TYPE.validateMoveInput) { //if the user has dropped the piece or has clicked another square after the first click

        const promo = color === COLOR.black ? '2' : '7'
        if ((event.squareTo.charAt(1) == "8" || event.squareTo.charAt(1) == "1") && event.piece.charAt(1) === "p" && (event.squareFrom.charAt(1) === promo)) { //check if the move is a pawn promotion

            var move = event.squareFrom + event.squareTo //define the variable move that will be needed to tell the chess object which move was played

            board.showPromotionDialog(event.squareTo, color, (event) => { //function that shows the promotion dialog

                if (event.piece) { //if the user has clicked one piece between queen,rook,bishop or knight

                    move = move + event.piece[1] //the piece (q,r,b,n) is added to the move

                    if (move === movesPlayer[counter]) { //if the move was the one in the solution array of the user's moves

                        chess.move(move) //make the move

                        board.setPosition(chess.fen(), true) //make the animation 

                        board.removeArrows(ARROW_TYPE.pointy)
                        board.removeMarkers(MARKER_TYPE.square)

                        board.addMarker(MARKER_TYPE.square, move[0] + move[1]) //add the square type marker of the last move
                        board.addMarker(MARKER_TYPE.square, move[2] + move[3]) //add the square type marker of the last move

                        makePcMove() //calls the makePcMove

                        counter += 1 //increse the counter that will be used to keep track of the solution
                    }

                    else { //if it isn't the right move

                        $("#state").removeClass("right");
                        $("#state").text("That's the wrong move");
                        $("#state").addClass("fail");

                        //still makes an animation and uses the chess.undo() function to delete the last move.
                        //it's a quick animation
                        chess.move(move) //make the move even if it's wrong
                        board.setPosition(chess.fen(), true) //make the animation
                        chess.undo() //delete the last move
                        board.setPosition(chess.fen(), true) //make the animation with the original chessboard

                    }

                } else { //if the user didn't clicked any of the piece showd in the promotion dialog

                    board.setPosition(chess.fen()) //set the position to its original
                }
            })
        }
        else { //if it's a regular move

            const move = { from: event.squareFrom, to: event.squareTo } //create an object from the squareFrom and squareTo

            try { //need a try catch because chess.js library fires an exception if a move is not valid
                result = chess.move(move) //return the object move. It's a valid move
            }
            catch (error) {

                result = null //the move was not valid

            }
            if (result) { //if is not null

                if (move.from + move.to === movesPlayer[counter]) { //checks if it is the right move to solve the puzzle

                    this.chessboard.state.moveInputProcess.then(() => {

                        board.removeMarkers(MARKER_TYPE.square) //remove all the square type markers that indicated the previous move 
                        board.removeArrows(ARROW_TYPE.pointy)

                        board.setPosition(chess.fen(), true) //set the animation of the move

                        event.chessboard.addMarker(MARKER_TYPE.square, event.squareFrom) //add the square type marker of the last move
                        event.chessboard.addMarker(MARKER_TYPE.square, event.squareTo) //add the square type marker of the last move

                        makePcMove() //calls the makePcMove
                    })
                }
                else {
                    this.chessboard.state.moveInputProcess.then(() => {

                        board.setPosition(chess.fen(), true) //make the animation
                        chess.undo() //delete the last move
                        board.setPosition(chess.fen(), true) //make the animation with the original chessboard

                        $("#state").removeClass("right");
                        $("#state").text("That's the wrong move");
                        $("#state").addClass("fail");
                    })
                }
            }
        }
        return result
    }
}

/**
 * Makes the move of the solution contained in the movesPc array based on the 
 * counter
 */
function makePcMove() {


    $("#state").removeClass("fail");
    $("#state").addClass("right");
    $("#state").text("That's the correct move\nkeep going");
    try {

        var first = movesPc[counter].slice(0, 2) //takes the first two char
        var second = movesPc[counter].slice(2, 4) //takes the last two char

        chess.move(movesPc[counter]) //make the move

        setTimeout(() => { board.removeMarkers(MARKER_TYPE.square) }, 750) //removes the markes of the previous move

        setTimeout(() => { board.setPosition(chess.fen(), true) }, 750) //make the animation 

        setTimeout(() => { board.addMarker(MARKER_TYPE.square, first) }, 750) //add the square type marker of the last move
        setTimeout(() => { board.addMarker(MARKER_TYPE.square, second) }, 750) //add the square type marker of the last move

        counter += 1 //increse the counter that will be used to keep track of the solution
    }
    //if this is triggered the user has solved the puzzle because the array wil be out of index
    //and the variable will not be defined
    catch (err) {
        counter = 0

        $("#state").removeClass("right");
        $("#state").text("Congratulation,\nyou solved the puzzle");
        $("#state").addClass("success");
    }
}
$("#hint").click(function () {
    board.removeArrows(ARROW_TYPE.pointy)
    board.addArrow(ARROW_TYPE.pointy, movesPlayer[counter][0] + movesPlayer[counter][1], movesPlayer[counter][2] + movesPlayer[counter][3])
})



