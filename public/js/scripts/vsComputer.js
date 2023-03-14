import { Chess } from "./../libraries/chess.js/chess.js";
import { INPUT_EVENT_TYPE, COLOR, Chessboard } from "./../libraries/cm-chessboard/Chessboard.js"
import { MARKER_TYPE, Markers } from "./../libraries/cm-chessboard/extensions/markers/Markers.js"
import { PromotionDialog } from "./../libraries/cm-chessboard/extensions/promotion-dialog/PromotionDialog.js"
import { FEN } from "./../libraries/cm-chessboard/model/Position.js"

var audio = new Audio("audio/move.mp3")
var chess = new Chess()
var board = new Chessboard(document.getElementById("board"), {
  position: FEN.start,
  sprite: {
    url: "images/chessboard/chessboard-sprite.svg"
  },
  extensions: [{
    class: Markers,
    props: {}
  },
  {
    class: PromotionDialog,
    props: {}
  }]
})


var result = null
const color = Math.floor(Math.random() * 2) === 0 ? COLOR.black : COLOR.white
board.setOrientation(color)

color === COLOR.black ? sendAjax(chess.fen()) : null


function inputHandler(event) {
  event.chessboard.removeMarkers(MARKER_TYPE.dot)
  if (event.type === INPUT_EVENT_TYPE.moveInputStarted) {
    const moves = chess.moves({ square: event.square, verbose: true });
    for (const move of moves) { // draw dots on possible squares
      event.chessboard.addMarker(MARKER_TYPE.dot, move.to)
    }
    return moves.length > 0
  } else if (event.type === INPUT_EVENT_TYPE.validateMoveInput) {
    if (event.squareTo.charAt(1) == "8" && event.piece.charAt(1) === "p") {
      var move = event.squareFrom + event.squareTo
      board.showPromotionDialog(event.squareTo, color, (event) => {
        if (event.piece) {
          move = move + event.piece[1]
          chess.move(move)
          board.setPosition(chess.fen(), true)
          //audio.play()
          event.chessboard.addMarker(MARKER_TYPE.square, event.squareFrom)
          sendAjax(chess.fen())

        } else {
          board.setPosition(board.getPosition())
        }
      })
    }
    else {
      const move = { from: event.squareFrom, to: event.squareTo }
      try {
        result = chess.move(move)
      }
      catch (error) {
        result = null
      }
      if (result) {
        this.chessboard.state.moveInputProcess.then(() => {
          board.removeMarkers(MARKER_TYPE.square)
          board.setPosition(chess.fen(), true)
          //audio.play()
          event.chessboard.addMarker(MARKER_TYPE.square, event.squareFrom)
          event.chessboard.addMarker(MARKER_TYPE.square, event.squareTo)
          sendAjax(chess.fen())
          //audio.play()

        })
      }
    }
    return result
  }
}

board.enableMoveInput(inputHandler, color)


function sendAjax(fen) {
  $.ajax({
    type: "POST",
    url: "index.php",
    data: {
      request: "get_move_pc",
      fen: fen,
      fileName: sessionId,
      skill: 0
    },
    dataType: "json",
    success: function (response) {
      
      setTimeout(() => { board.removeMarkers(MARKER_TYPE.square) }, 1500)
      //audio.play()
      chess.move(response)
      setTimeout(() => { board.setPosition(chess.fen(), true) }, 1500)
      
      setTimeout(() => { board.addMarker(MARKER_TYPE.square, response[0] + response[1]) }, 1500)
      setTimeout(() => { board.addMarker(MARKER_TYPE.square, response[2] + response[3]) }, 1500)
    }
  });
}
$(document).ready(function () {

  $('#board').on('scroll touchmove touchend touchstart contextmenu', function (e) {
    e.preventDefault();
  });
})



