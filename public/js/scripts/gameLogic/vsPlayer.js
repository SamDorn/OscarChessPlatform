import { INPUT_EVENT_TYPE, COLOR } from "../../libraries/cm-chessboard/Chessboard.js"
import { MARKER_TYPE } from "../../libraries/cm-chessboard/extensions/markers/Markers.js"
import { ARROW_TYPE } from "../../libraries/cm-chessboard/extensions/arrows/Arrows.js"
import { initializeChessboard, chess, board, playAudio, drawArraws, getMove } from "./game.js";


initializeChessboard()

drawArraws()

var color = null
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
                        $("#finish").text("Congratulations. You won")
                    }
                    else {
                        getMove(socket, chess, board) //calls the function to get the pc move and update the chessboard
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

                    socket.send(JSON.stringify({
                        request: 'vsPlayer',
                        state: 'update',
                        jwt: jwt,
                        pgn: chess.pgn(),
                        move: event.squareFrom + event.squareTo,
                        id: gameId
                    }))

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
                    }

                })
            }
        }
        return result
    }
}
if (socket.readyState === WebSocket.OPEN) {
    socket.send(JSON.stringify({
        request: 'vsPlayer',
        jwt: jwt,
        state: 'new game',
        color: null,
        id: gameId
    }))

}
else {
    socket.addEventListener('open', function () {
        socket.send(JSON.stringify({
            request: 'vsPlayer',
            jwt: jwt,
            state: 'new game',
            color: null,
            id: gameId
        })
        )
    })
}
var i = 0
socket.onmessage = function (e) {
    var data = JSON.parse(e.data)
    console.log(data)
    console.log(data.color)
    try {
        if(data.status == "ready to play"){
            $("#board").removeClass("hidden");
            $(".ring").addClass("hidden");
        }
    } catch (error) {
        console.log(error)
        
    }
    if (i === 0) {
        color = data.color === 'white' ? COLOR.white : COLOR.black
        board.setOrientation(color)
        board.enableMoveInput(inputHandler, color) //enable the input for the color of the user
        i++
    }
    
    try {
        chess.loadPgn(data.pgn)
        board.setPosition(chess.fen(), true)
        $("#board").removeClass("hidden");
        $(".ring").addClass("hidden");
        board.removeMarkers(MARKER_TYPE.square)
        board.addMarker(MARKER_TYPE.square, data.move[0] + data.move[1])
        board.addMarker(MARKER_TYPE.square, data.move[2] + data.move[3])
    } catch (error) {

    }
    try {
        board.addMarker(MARKER_TYPE.square, data.last_move[0] + data.last_move[1])
        board.addMarker(MARKER_TYPE.square, data.last_move[2] + data.last_move[3])
        
    } catch (error) {
        
    }

    gameId = data.id_game

    
}
$(".button").click(function (e) {
    e.preventDefault();
    socket.send(JSON.stringify({
        request: 'vsPlayer',
        state: 'delete',
        jwt: jwt
    }))
    location.href = "home"
    
});
