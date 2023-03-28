var board = null
var game = new Chess()
var socket = new WebSocket('ws://172.20.10.3:8080');

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

function onDragStart(source, piece, position, orientation) {

    // do not pick up pieces if the game is over
    if (game.game_over()) return false

    if (color == "white")
        colorOpp = "b"
    else
        colorOpp = "w"
    // only pick up pieces for White
    //if (piece.search(/^b/) !== -1) return false
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
    // see if the move is legal
    var move = game.move({
        from: source,
        to: target,
        promotion: 'q' // NOTE: always promote to a queen for example simplicity
    })

    // illegal move
    if (move === null) return 'snapback'



}
var gameId = null
// update the board position after the piece snap
// for castling, en passant, pawn promotion
function onSnapEnd() {
    board.position(game.fen())
    console.log(gameId)

    socket.send(JSON.stringify({ gameId: gameId, fen: game.fen() }))
}


var config = {
    draggable: true,
    position: 'start',
    onDragStart: onDragStart,
    onDrop: onDrop,
    onSnapEnd: onSnapEnd
}
var color = null
socket.onmessage = function (e) {
    var data = JSON.parse(e.data)
    console.log(data)
    if (data.status == "game terminated") {
        var turn = game.turn()
        if (turn == 'b')
            turn = 'Il bianco'
        else if (turn == 'w')
            turn = 'Il nero'
        alert("Gioco finito. Ha vinto " + turn)
    }
    board.orientation(data.color)
    if (data.color !== undefined)
        color = data.color
    if (data.status === "Waiting for a second player")
        $("#prova").show()
    else {
        $("#myBoard").show()
        $("#prova").hide()

    }
    gameId = data.gameId

    //document.cookie = "gameId=" + gameId + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";

    board.position(data.fen)
    game.load(data.fen)
    if (game.game_over()) {
        socket.send(JSON.stringify({ gameId: gameId, status: "game_over" }))
        $("#rigioca").show()
    }
}