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


function onDragStart(source, piece) {
  if (game.game_over()) return false

  if (game.turn() === colorOpp || (piece.search(new RegExp('^' + colorOpp)) !== -1)) return false

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

function onDrop(source, target) {
  removeGreySquares();

  var move = game.move({
    from: source,
    to: target,
    promotion: 'q' // NOTE: always promote to a queen for example simplicity
  })

  // illegal move
  if (move === null) return 'snapback';


  //highlight(source, target)

}

function onSnapEnd() {
  board.position(game.fen())

  if (game.game_over()) {

    removeLocalStorage()

    alert("Hai Vinto")

    $('#quit').hide()
    $('#restart').show();
  }


  if (!game.game_over()) {
    sendAjax(game.fen(), sessionId, skill)

  }

}
function sendAjax(fen, fileName, skill) {
  $.ajax({
    url: "index.php",
    type: "GET",
    data: {
      request: 'get_move_pc',
      fen: fen,
      fileName: fileName,
      skill: skill
    },
    dataType: "json",
    success: function (data) {
      board.position(data)
      localStorage.setItem('fen', data)
      game.load(data)
      if (game.game_over()) {
        removeLocalStorage()
        alert("Hai Perso")

        $('#quit').hide()
        $('#restart').show();

      }
    }
  })
}
function removeLocalStorage() {

  localStorage.removeItem('fen')
  localStorage.removeItem('skill')
  localStorage.removeItem('colorOpp')

}

var config = {
  draggable: true,
  position: 'start',
  onDragStart: onDragStart,
  onDrop: onDrop,
  onSnapEnd: onSnapEnd,
}



