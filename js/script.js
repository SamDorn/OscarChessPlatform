var board = null
var game = new Chess()

var whiteSquareGrey = '#a9a9a9'
var blackSquareGrey = '#696969'

function getSkill(id){
   return document.getElementById(id).value
}

function removeGreySquares () {
    $('#myBoard .square-55d63').css('background', '')
  }
  
  function greySquare (square) {
    var $square = $('#myBoard .square-' + square)
  
    var background = whiteSquareGrey
    if ($square.hasClass('black-3c85d')) {
      background = blackSquareGrey
    }
  
    $square.css('background', background)
  }

function onDragStart(source, piece){
    if (game.game_over()) return false

    if(game.turn() === 'b' || piece.search(/^b/) !== -1) return false

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

function onDrop(source, target){
    removeGreySquares();

    var move = game.move({
        from: source,
        to: target,
        promotion: 'q' // NOTE: always promote to a queen for example simplicity
      })
    
      // illegal move
      if (move === null) return 'snapback';
}

function onSnapEnd(){
    board.position(game.fen())
    console.log(game.fen())
    $.ajax({
        url: "ajax.php",
        type: "GET",
        data: { 
            fen: game.fen(), 
            fileName: sessionId,
            skill: skill
        },
        dataType: "json",
        success: function(data){
          board.position(data)
          game.load(data)
          console.log(data)
        }
      })
}
var config = {
    draggable: true,
    position: 'start',
    onDragStart: onDragStart,
    onDrop: onDrop,
    onSnapEnd: onSnapEnd,
}


