import { Chess } from "../../libraries/chess.js/chess.js"
import { Chessboard } from "../../libraries/cm-chessboard/Chessboard.js"
import { COLOR } from "../../libraries/cm-chessboard/Chessboard.js"
import { Markers, MARKER_TYPE } from "../../libraries/cm-chessboard/extensions/markers/Markers.js"
import { PromotionDialog } from "../../libraries/cm-chessboard/extensions/promotion-dialog/PromotionDialog.js"
import { Arrows, ARROW_TYPE } from "../../libraries/cm-chessboard/extensions/arrows/Arrows.js"
import { FEN } from "../../libraries/cm-chessboard/model/Position.js"

export var chess = new Chess
export var board

/**
 * Initialize the board with all the extension
 */
export function initializeChessboard() {
    board = new Chessboard(document.getElementById("board"), {
        position: FEN.start,
        sprite: {
            url: "images/chessboard/chessboard-sprite.svg" //url to chess pieces images
        },


        extensions: [
            //extension to allow the drawing of the dots for legal move and to display
            //the last move played
            {
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
}

var audioMove = new Audio("audio/chess_move.mp3")
var audioCastle = new Audio("audio/chess_castle.mp3")
var audioCapture = new Audio("audio/chess_capture.mp3")
/**
 * If the move contains an "x" (indicating a capture), play the capture sound. If the move contains an
 * "O" (indicating a castle), play the castle sound. Otherwise, play the move sound
 * @param {Object} move The move object when a move is played
 */
export function playAudio(move) {
    move.san.indexOf("x") > -1 ? audioCapture.play() : null
    move.san.indexOf("O") > -1 ? audioCastle.play() : null
    move.san.indexOf("O") === -1 && move.san.indexOf("x") === -1 ? audioMove.play() : null

}
/**
 * When the user clicks the hint button, the function makes an ajax call to the server, which returns a
 * move, and then the function draws an arrow on the board to show the move.
 * @param {string} color The color of the player
 */
export function hintClick(color, socket) {
    $('#hint').click(function () {
        board.removeArrows()
        if (chess.turn() === color) {
            socket.send(JSON.stringify({
                request: 'vsComputer',
                username: sessionId,
                fen: chess.fen(),
                skill:20
            }))
            socket.onmessage = function (e){
                let object = JSON.parse(e.data)
                board.addArrow(ARROW_TYPE.pointy, object.move[0] + object.move[1], object.move[2] + object.move[3])
            }
        }
    })
}
/**
 * It adds a circle marker to the board when the user right clicks on a square, 
 * and adds an arrow when
 * the user right clicks on one square and then another.
 */
export function drawArraws() {
    let square = null
    /* It removes the arrows and markers when the user clicks on the board. */
    $("#board").click(function () {
        board.removeArrows(ARROW_TYPE.default)
        board.removeMarkers(MARKER_TYPE.circle)
    })
    /* Setting the square variable to the square that the user right clicks on. */
    $("#board").mousedown(function (e) {
        if (e.which !== 3)
            return
        square = e.target.dataset.square
    })
    $("#board").mouseup(function (e) {
        if (e.which !== 3)
            return

        if (square === e.target.dataset.square) {
            board.addMarker(MARKER_TYPE.circle, e.target.dataset.square)
        }
        else {
            board.addArrow(ARROW_TYPE.default, square, e.target.dataset.square)
        }
    })
    /* Preventing the default context menu from showing up. */
    $("#board").contextmenu(function (e) {

        e.preventDefault()
        return
    })
}
var solution = null
var movesPlayer = []
var movesPc = []
/**
* It gets the pgn of the puzzle and loads it into the chess object. Then it sets the orientation of
* the board based on the color to move. Finally it creates two arrays, one for the moves that need to
* be played by the user and one for the moves that need to be played by the computer
* @param {string} data
* @param {string} color
*/
export function getPuzzle(data, color, inputHandler) {
    $.ajax({
        type: "GET",
        url: "https://lichess.org/api/puzzle/" + data, //endpoint that gets the information needed
        dataType: "json",
        success: function (response) {
            chess.loadPgn(response.game.pgn) //load the chess object with the pgn from the response

            //usage of the ternary operator to determine if the color the user is playing 
            //is white or black. If the color to move is black it will be black otherwise white
            color = chess.turn() == 'b' ? COLOR.black : COLOR.white
            color === COLOR.white ? $("#player").text("muove il bianco") : $("#player").text("Muove il nero")

            board.setPosition(chess.fen())
            board.setOrientation(color) //set the orientation of the board based on the color

            //enable the move input calling the input handler and the color
            //so that the user can only pick up pieces from its color
            board.enableMoveInput(inputHandler, color)

            //array that contains all the moves that need to be played
            //both by the player and the computer
            solution = response.puzzle.solution
            movesPlayer = []
            movesPc = []

            /**
             * We know that the first move will be played by the user
             * so we can create two array. One for the moves that needs
             * to be played by the user and one for the computer.
             * this way we can keep track of the moves
            */
            for (let i = 0; i < solution.length; i++) {
                if (i % 2 == 0) { // if it's even it's added to the user's moves
                    movesPlayer.push(solution[i]);
                } else {
                    movesPc.push(solution[i]); //if it's odd it's added to the pc's moves
                }
            }

        }
    })
}
export var movesPc
export var movesPlayer

/**
 * When the user clicks on a category, the function will call the API to get the puzzle ID, then call
 * the API again to get the puzzle data.
 * @param {string} category - the category of the puzzle
 */
export function getPuzzleByCategory(category, color, inputHandler) {

    $("#" + category).click(function () {
        board.removeArrows()
        board.removeMarkers()

        $("#state").text("");
        $("#title").text("Puzzle category:" + category);
        $("#loading").removeClass("remove");
        $("#board").addClass("remove");

        $.ajax({
            type: "GET",
            url: "puzzle",
            data: {
                keyword: category
            },
            dataType: "json",
            success: function (response) {

                getPuzzle(response, color, inputHandler)

                $("#loading").addClass("remove");
                $("#board").removeClass("remove");
            }
        });
    })
}

export function getMove(socket, chess, board) {

    if (socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify({
            request: "vsComputer",
            username: sessionId,
            fen: chess.fen(),
            skill: 20,
            jwt: jwt
        }))

    }
    else {
        socket.addEventListener('open', function () {
            socket.send(JSON.stringify({
                request: "vsComputer",
                username: sessionId,
                fen: chess.fen(),
                skill: 20,
                jwt: jwt
            })
            )
        })
    }


    socket.onmessage = function (e) {
        let object = JSON.parse(e.data)
        let move = chess.move(object.move)
        setTimeout(() => { board.setPosition(chess.fen(), true) }, 1500)
        setTimeout(() => { board.removeMarkers(MARKER_TYPE.square) }, 1500) //removes the markers of the previous move

        setTimeout(() => { board.setPosition(chess.fen(), true) }, 1500) //make theanimation of the move
        setTimeout(() => { playAudio(move) }, 1500)

        setTimeout(() => { $("#pgn").html(chess.pgn()) }, 1500)
        setTimeout(() => { board.addMarker(MARKER_TYPE.square, object.move[0] + object.move[1]) }, 1500) //add the square type marker of the last move
        setTimeout(() => { board.addMarker(MARKER_TYPE.square, object.move[2] + object.move[3]) }, 1500) //add the square type marker of the last move
        setTimeout(() => {
            if (chess.isGameOver()) {
                if (chess.isDraw()) {
                    $("#finish").text("Pareggio. Nessuno vince")
                }
                else {
                    $("#finish").text("Sembra che hai perso. Allenati ancora un pó")
                }
            }
        }, 1500)
    }
}