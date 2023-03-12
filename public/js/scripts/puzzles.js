var game = new Chess()
var board = null
var pgn = null
var config = null
var solution = null
var colorOpp = null
var movesPlayer = []
var movesPc = []
var whiteSquareGrey = '#a9a9a9'
var blackSquareGrey = '#696969'
var counter = 0
var movePlayed = null


function removeGreySquares() {
    $('#myBoard .square-55d63').css('background', '')
}


function greySquare(square) {
    var $square = $('#myBoard .square-' + square)

    var background = whiteSquareGrey
    if ($square.hasClass('black-3c85d')) {
        background = blackSquareGrey
    }
    $square.css('background', background)
}


function onDragStart(source, piece) {

    if (game.turn() === colorOpp || piece.search(new RegExp('^' + colorOpp)) !== -1) return false

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
    movePlayed = source + target





    //highlight(source, target)

}

function onSnapEnd() {
    board.position(game.fen())
    if (movePlayed !== movesPlayer[counter]) {
        game.undo()
        board.position(game.fen())
        alert("Incorrect move")
    }
    else {
        try {
            game.move({
                from: movesPc[counter][0],
                to: movesPc[counter][1]
            })
            console.log(game.fen())
            board.position(game.fen())
            counter += 1
        } catch (e) {
            alert("Puzzle solved")
        }

    }
}


//function that calls the api to get the daily puzzles
$.ajax({
    type: "GET",
    url: "https://lichess.org/api/puzzle/daily",
    data: "data",
    dataType: "json",
    success: function (response) {
        pgn = response.game.pgn.split(" ")
        solution = response.puzzle.solution
        for (let i = 0; i < pgn.length; i++) {
            game.move(pgn[i])
        }
        for (let i = 0; i < solution.length; i++) {
            if (i % 2 == 0) {
                movesPlayer.push(solution[i]);
            } else {
                movesPc.push(solution[i]);
            }
        }
        for (let i = 0; i < movesPc.length; i++) {

            // Find the position of the first number
            let numIndex = movesPc[i].search(/\d/) + 1;

            // Extract the two values using the substring method
            let value1 = movesPc[i].substring(0, numIndex);
            let value2 = movesPc[i].substring(numIndex);

            // Update the original array with the new values
            movesPc[i] = [value1, value2];
        }
        console.log(movesPc)
        colorOpp = "b"
        if (game.turn() == "b")
            colorOpp = "w"
        config = {
            draggable: true,
            position: game.fen(),
            onDragStart: onDragStart,
            onDrop: onDrop,
            onSnapEnd: onSnapEnd,
        }
        initChessBoard()
    }
})
function initChessBoard() {
    $(document).ready(function () {

        board = Chessboard("myBoard", config)
        if (game.turn() == 'b')
            board.orientation("black")
    });
}




