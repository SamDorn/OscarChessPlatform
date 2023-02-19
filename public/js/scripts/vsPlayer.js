var board = null
var game = new Chess()

var whiteSquareGrey = '#a9a9a9'
var blackSquareGrey = '#696969'
var prova = '#fff'

function removeGreySquares() {
  $('#myBoard .square-55d63').css('background', '')
}

/*function highlight(square, target){
  var $square = $('#myBoard .square-' + square)
  var $target = $('#myBoard .square-' + target)


  $square.addClass("highlight-white")
  $target.addClass("highlight-white")
}*/


function greySquare(square) {
  var $square = $('#myBoard .square-' + square)

  var background = whiteSquareGrey
  if ($square.hasClass('black-3c85d')) {
    background = blackSquareGrey
  }

  $square.css('background', background)
}

function onDragStart (source, piece, position, orientation) {
  // do not pick up pieces if the game is over
  if (game.game_over()) return false

  // only pick up pieces for White
  if (piece.search(/^b/) !== -1) return false

  var moves = game.moves({
    square: source,
    verbose: true,
  })

  if (moves.lenght == 0) return

  greySquare(source)

  for (var i = 0; i < moves.length; i++) {
    greySquare(moves[i].to)
  }
}


function onDrop (source, target) {
  removeGreySquares();
  // see if the move is legal
  var move = game.move({
    from: source,
    to: target,
    promotion: 'q' // NOTE: always promote to a queen for example simplicity
  })

  // illegal move
  if (move === null) return 'snapback'

}

// update the board position after the piece snap
// for castling, en passant, pawn promotion
function onSnapEnd () {
  board.position(game.fen())
  
}

var config = {
  draggable: true,
  position: 'start',
  onDragStart: onDragStart,
  onDrop: onDrop,
  onSnapEnd: onSnapEnd
}