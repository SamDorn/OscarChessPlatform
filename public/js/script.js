var board = null
var game = new Chess()

var whiteSquareGrey = '#a9a9a9'
var blackSquareGrey = '#696969'
var prova = '#fff'


function removeGreySquares () {
    $('#myBoard .square-55d63').css('background', '')
  }

/*function highlight(square, target){
  var $square = $('#myBoard .square-' + square)
  var $target = $('#myBoard .square-' + target)


  $square.addClass("highlight-white")
  $target.addClass("highlight-white")
}*/

  
function greySquare (square) {
    var $square = $('#myBoard .square-' + square)

    var background = whiteSquareGrey
    if ($square.hasClass('black-3c85d')) {
      background = blackSquareGrey
  }

  $square.css('background', background)
  }

function onDragStart(source, piece){
  if(skill === undefined){
    alert("SELEZIONA UN LIVELLO: DA 0 A 20")
    return false
  }
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
        promotion: 'r' // NOTE: always promote to a queen for example simplicity
      })
      // illegal move
      if (move === null) return 'snapback';

      //highlight(source, target)
      
}

function onSnapEnd(){
    board.position(game.fen())
    
    if(!game.game_over()){
      $.ajax({
        url: "index.php",
        type: "GET",
        data: { 
          request: 'get_move_no_login',
          fen: game.fen(), 
          fileName: sessionId,
          skill: skill
        },
        dataType: "json",
        success: function(data){
          board.position(data)
          game.load(data)
          localStorage.setItem("fen", data)
          
        }
      })
        
    }
   
    
    else{
      $.ajax({
        url: "index.php",
        type: "GET",
        data: { 
            fen: "gameOver", 
            fileName: sessionId,
            skill: skill
        },
        dataType: "json",
        success: function(data){
          alert("Game ended")
        }
      })
      
    }



}
var config = {
    draggable: true,
    position: 'start',
    onDragStart: onDragStart,
    onDrop: onDrop,
    onSnapEnd: onSnapEnd,
}

function getFileContent(){
  $.get("../app/generated_files/" + sessionId + '.txt', function(data) {
    game.load(data)
    board.position(data)
 }, 'text');
 skill = 0;

}

function setStorage(){
  localStorage.setItem("skill", skill)
}


