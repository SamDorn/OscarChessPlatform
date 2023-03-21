import { Chess } from "./../libraries/chess.js/chess.js";
import { INPUT_EVENT_TYPE, COLOR, Chessboard } from "./../libraries/cm-chessboard/Chessboard.js"
import { MARKER_TYPE, Markers } from "./../libraries/cm-chessboard/extensions/markers/Markers.js"
import { PromotionDialog } from "./../libraries/cm-chessboard/extensions/promotion-dialog/PromotionDialog.js"
import { FEN } from "./../libraries/cm-chessboard/model/Position.js"
import { ARROW_TYPE, Arrows } from "./../libraries/cm-chessboard/extensions/arrows/Arrows.js"

/*
var time_in_minutes = 0.5;
var current_time = Date.parse(new Date());
var deadline = new Date(current_time + time_in_minutes * 60 * 1000);


function time_remaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return { 'total': t, 'days': days, 'hours': hours, 'minutes': minutes, 'seconds': seconds };
}

var timeinterval;
function run_clock(id, endtime) {
  var clock = document.getElementById(id);
  function update_clock() {
    var t = time_remaining(endtime);
    clock.innerHTML = 'minutes: ' + t.minutes + '<br>seconds: ' + t.seconds;
    if (t.total <= 0) { clearInterval(timeinterval); }
  }
  update_clock(); // run function once at first to avoid delay
  timeinterval = setInterval(update_clock, 1000);
}
//run_clock('min1',deadline);


var paused = false; // is the clock paused?
var time_left; // time left on the clock when paused

function pause_clock() {
  if (!paused) {
    paused = true;
    clearInterval(timeinterval); // stop the clock
    time_left = time_remaining(deadline).total; // preserve remaining time
  }
}

function resume_clock() {
  if (paused) {
    paused = false;
    console.log(time_left)
    // update the deadline to preserve the amount of time remaining
    deadline = new Date(Date.parse(new Date()) + time_left);

    // start the clock
    run_clock('min1', deadline);
  }
}

// handle pause and resume button clicks
document.getElementById('pause').onclick = pause_clock;
document.getElementById('resume').onclick = resume_clock;

run_clock('min1', deadline);

*/



var chess = new Chess() //chess move validator
var board = new Chessboard(document.getElementById("board"), { //chessboard
  position: FEN.start,
  sprite: {
    url: "images/chessboard/chessboard-sprite.svg" //url to chess pieces images
  },
  extensions: [
    {
      //extension to allow the drawing of the dots for legal move and to display
      //the last move played
      class: Markers,
      props: {}
    },
    //extension to allow the view of the promotion dialog when a pawn is getting promoted
    {
      class: PromotionDialog,
      props: {}
    },
    {
      class: Arrows,
      props: {
        sprite: {
          url: "images/chessboard/arrows.svg"
        }
      }
    }
  ]
})

var result = null

//uses the ternary operator to casually generate the color
//const color = Math.floor(Math.random() * 2) === 0 ? COLOR.black : COLOR.white
var color = COLOR.white

board.setOrientation(color) //set the orientation of the board based on the color

//uses the ternary operator to see if the user is black.
//if it is it calls the sendAjax which will make the first move as white
//color === COLOR.black ? sendAjax(chess.fen()) : null

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
          chess.move(move) //make the move

          board.setPosition(chess.fen(), true) //make the animation

          board.addMarker(MARKER_TYPE.square, move[0] + move[1]) //add the square type marker of the last move
          board.addMarker(MARKER_TYPE.square, move[2] + move[3]) //add the square type marker of the last move
          if (chess.isGameOver()) {
            $("#finish").text("Congratulazioni. Hai vinto")
          }
          else {
            sendAjax(chess.fen()) //calls sendAjax
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

          board.setPosition(chess.fen(), true) //make the animation

          event.chessboard.addMarker(MARKER_TYPE.square, event.squareFrom) //add the square type marker of the last move
          event.chessboard.addMarker(MARKER_TYPE.square, event.squareTo) //add the square type marker of the last move

          if (chess.isGameOver()) {
            if (chess.isDraw()) {

              $("#finish").text("Pareggio. Nessuno vince")

            }
            else {

              $("#finish").text("Congratulazioni. Hai vinto")
            }
          }
          else {
            sendAjax(chess.fen()) //calls thefunction to get the pc move and update the chessboard
          }

        })
      }
    }
    return result
  }
}

board.enableMoveInput(inputHandler, color) //enable the input for the color of the user

/**
 * Send an ajax request to the index.php and the response is the best move 
 * according to the skill. It will update the chessboard
 * @param {string} fen 
 */
function sendAjax(fen) {

  $.ajax({
    type: "POST",
    url: "index.php",
    /**
   * The script will receive 4 attributes:
   * 1- The request which is needed to identify the ajax operation
   * 2- The fen which is the board where the chess engine calculate the best move
   * 3- The fileName which is the sessionId(session_id()) to have uniques filenames
   * 4- The skill level based on the user choice at the beginning of the match
   */
    data: {
      request: "get_move_pc",
      fen: fen,
      fileName: sessionId,
      skill: 0
    },
    dataType: "json",
    success: function (response) {

      setTimeout(() => { board.removeMarkers(MARKER_TYPE.square) }, 1500) //removes the markers of the previous move

      setTimeout(() => { chess.move(response) }, 1500) //make the move received from the script

      setTimeout(() => { board.setPosition(chess.fen(), true) }, 1500) //make theanimation of the move

      setTimeout(() => { board.addMarker(MARKER_TYPE.square, response[0] + response[1]) }, 1500) //add the square type marker of the last move
      setTimeout(() => { board.addMarker(MARKER_TYPE.square, response[2] + response[3]) }, 1500) //add the square type marker of the last move

      if (chess.isGameOver()) {
        if (chess.isDraw()) {
          setTimeout(() => { $("#finish").text("Pareggio. Nessuno vince") }, 1500)
        }
        else {
          setTimeout(() => { $("#finish").text("Sembra che hai perso. Allenati ancora un pó") }, 1500)

        }
      }
    }
  });
}

/**
 * MUST test for iphone!!
 */
$(document).ready(function () {

  $('#board').on('scroll touchmove touchend touchstart contextmenu', function (e) {
    //e.preventDefault();

  })
})

$('#hint').click(function (e) {
  board.removeArrows()
  var c = color === COLOR.black ? "b" : "w"
  if (chess.turn() === c) {
    $.ajax({
      type: "POST",
      url: "index.php",
      data: {
        request: "get_move_pc",
        skill: 20,
        filename: sessionId,
        fen: chess.fen()
      },
      dataType: "json",
      success: function (response) {
        var from = response[0] + response[1]
        var to = response[2] + response[3]
        board.addArrow(ARROW_TYPE.pointy, from, to)

      }
    })
  }

})
var prova = null

$("#board").click(function (e) {
  e.preventDefault();
  board.removeArrows(ARROW_TYPE.default)
  board.removeMarkers(MARKER_TYPE.circle)
})
$("#board").mousedown(function (e) {
  if (e.which !== 3)
    return
  prova = e.target.dataset.square
})
$("#board").mouseup(function (e) {
  //console.log(e.target.dataset.square)
  if (e.which !== 3)
    return

  if (prova === e.target.dataset.square) {
    board.addMarker(MARKER_TYPE.circle, e.target.dataset.square)
  }
  else {
    board.addArrow(ARROW_TYPE.default, prova, e.target.dataset.square)
  }
}


);
$("#board").contextmenu(function (e) {
  e.preventDefault()
  return
})
$("#menu").click(function (e) { 
  location.href = "home"
});
