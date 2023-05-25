import { INPUT_EVENT_TYPE, COLOR } from "../../libraries/cm-chessboard/Chessboard.js"
import { MARKER_TYPE } from "../../libraries/cm-chessboard/extensions/markers/Markers.js"
import { ARROW_TYPE } from "../../libraries/cm-chessboard/extensions/arrows/Arrows.js"
import { initializeChessboard, chess, board, playAudio, hintClick, drawArraws, getMove } from "./game.js";


// Create a new WebSocket connection
var socket = new WebSocket('ws://localhost:8080');

initializeChessboard()


var result = null






/**
 * Handles the input of the user
 * @param {*} event 
 * @returns 
 */
function inputHandler(event) {

  event.chessboard.removeMarkers(MARKER_TYPE.dot) //removes every dot that was generated

  if (event.type === INPUT_EVENT_TYPE.moveInputStarted) {
    const moves = chess.moves({ square: event.square, verbose: true }) //gets all the possible moves from that particular square

    // draw dots on possible squares
    for (const move of moves) {
      event.chessboard.addMarker(MARKER_TYPE.dot, move.to)
    }
    return moves.length > 0

  } else if (event.type === INPUT_EVENT_TYPE.validateMoveInput) {
    const promo = color === COLOR.black ? '2' : '7'

    if ((event.squareTo.charAt(1) == "8" || event.squareTo.charAt(1) == "1") && event.piece.charAt(1) === "p" && (event.squareFrom.charAt(1) === promo)) { //check if the move is a pawn promotion

      var move = event.squareFrom + event.squareTo //define the variable move that will be needed to tell the chess object which move was played

      board.showPromotionDialog(event.squareTo, color, (event) => { //function that shows the promotion dialog

        if (event.piece) {
          move = move + event.piece[1] //the piece (q,r,b,n) is added to the move

          board.removeArrows(ARROW_TYPE.pointy)
          let prova = chess.move(move) //make the move

          playAudio(prova)
          board.setPosition(chess.fen(), true) //make the animation


          board.addMarker(MARKER_TYPE.square, move[0] + move[1]) //add the square type marker of the last move
          board.addMarker(MARKER_TYPE.square, move[2] + move[3]) //add the square type marker of the last move
          if (chess.isGameOver()) {
            if (chess.isDraw()) {
              
              $("#modal-finish").removeClass("hidden");
              $("#title-modal").html("You drew:<br>You are a not a winner nor a loser");
            }
            else {
              $("#modal-finish").removeClass("hidden");
              $("#title-modal").html("You won:<br>You are a winner");
            }
          }
          else {
            getMove(socket, chess, board, skill) //calls the function to get the pc move and update the chessboard
          }

        } else { //if the user didn't clicked any of the piece showd in the promotion dialog
          board.setPosition(chess.fen()) //set the position to its original
        }
      })
    }
    else {

      const move = { from: event.squareFrom, to: event.squareTo } //create an object from the squareFrom and squareTo

      try { //need a try catch because chess.js library fires an exception if a move is not valid

        result = chess.move(move) //return the object move. It's a valid move

      }
      catch (error) {

        result = null //the move was not valid

      }
      if (result) { //if result isn't null
        this.chessboard.state.moveInputProcess.then(() => {
          board.removeArrows(ARROW_TYPE.pointy)

          board.removeMarkers(MARKER_TYPE.square) //removes the markers of the previous move
          playAudio(result)
          board.setPosition(chess.fen(), true) //make the animation
          $("#pgn").html(chess.pgn());

          event.chessboard.addMarker(MARKER_TYPE.square, event.squareFrom) //add the square type marker of the last move
          event.chessboard.addMarker(MARKER_TYPE.square, event.squareTo) //add the square type marker of the last move

          if (chess.isGameOver()) {
            if (chess.isDraw()) {

              $("#modal-finish").removeClass("hidden");
              $("#title-modal").html("You drew:<br>You are a not a winner nor a loser");

            }
            else {
              $("#modal-finish").removeClass("hidden");
              $("#title-modal").html("You won:<br>You are a winner");
            }
          }
          else {
            getMove(socket, chess, board, skill) //calls the function to get the pc move and update the chessboard
          }

        })
      }
    }
    return result
  }
}
function start(color, skill) {
  if (color === "random")
    color = Math.floor(Math.random() * 2) === 0 ? COLOR.black : COLOR.white
  else {
    color = color === "white" ? COLOR.white : COLOR.black
  }


  board.setOrientation(color) //set the orientation of the board based on the color

  //uses the ternary operator to see if the user is black.
  //if it is it send a message to the webSocket server to make the first move
  color === COLOR.black ? getMove(socket, chess, board, skill) : null
  board.enableMoveInput(inputHandler, color) //enable the input for the color of the user

  hintClick(color, socket)
}

drawArraws()


/* It redirects the user to
the home page. */
$("#menu").click(function () {
  location.href = "home"
})

$("#play").click(function (e) {
  e.preventDefault();
  if (color === null) {
    $("#error").html("Choose a color");
    $("#error").removeClass("hidden");
    return
  }
  if (skill === null) {

    $("#error").html("Choose a skill");
    $("#error").removeClass("hidden");
    return
  }
  $("#modal").remove();;
  start(color, skill)
});

