import { INPUT_EVENT_TYPE, COLOR } from "../../libraries/cm-chessboard/Chessboard.js"
import { MARKER_TYPE } from "../../libraries/cm-chessboard/extensions/markers/Markers.js"
import { ARROW_TYPE } from "../../libraries/cm-chessboard/extensions/arrows/Arrows.js"
import { initializeChessboard, chess, board, playAudio, drawArraws } from "./game.js";


initializeChessboard()

drawArraws()


var color = null
var result = null
var username = null


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
                    board.removeMarkers(MARKER_TYPE.square)
                    let prova = chess.move(move) //make the move

                    socket.send(JSON.stringify({
                        request: 'vsPlayer',
                        state: 'update',
                        jwt: jwt,
                        pgn: chess.pgn(),
                        move: move,
                        id: gameId
                    }))
                    playAudio(prova)
                    board.setPosition(chess.fen(), true) //make the animation


                    board.addMarker(MARKER_TYPE.square, move[0] + move[1]) //add the square type marker of the last move
                    board.addMarker(MARKER_TYPE.square, move[2] + move[3]) //add the square type marker of the last move
                    if (chess.isGameOver()) {
                        if (chess.isDraw()) {
                            console.log("pareggio")
                            socket.send(JSON.stringify({
                                request: 'vsPlayer',
                                state: 'finish',
                                jwt: jwt,
                                pgn: chess.pgn(),
                                msg: 'draw',
                                id: gameId
                            }))
                            modal.style.display = "block";
                            $("#title-modal").html("You drew:<br>You are a not a winner nor a loser");


                        } else {
                            console.log("vittoria")
                            socket.send(JSON.stringify({
                                request: 'vsPlayer',
                                state: 'finish',
                                jwt: jwt,
                                pgn: chess.pgn(),
                                msg: 'win',
                                id: gameId
                            }))
                            modal.style.display = "block";
                            $("#title-modal").html("You won:<br>You are a winner");
                        }
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

                    /**
                     * Send a message to the webSocket server telling to update the game and to send the
                     * move to the other player
                     */
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

                    /**
                     * After the player makes a move it is impossible to lose so if te game is finished
                     * it will be either a draw or a win for the player
                     */
                    if (chess.isGameOver()) {
                        if (chess.isDraw()) {
                            console.log("pareggio")
                            socket.send(JSON.stringify({
                                request: 'vsPlayer',
                                state: 'finish',
                                jwt: jwt,
                                pgn: chess.pgn(),
                                msg: 'draw',
                                id: gameId
                            }))
                            modal.style.display = "block";
                            $("#title-modal").html("You drew:<br>You are a not a winner nor a loser");


                        } else {
                            console.log("vittoria")
                            socket.send(JSON.stringify({
                                request: 'vsPlayer',
                                state: 'finish',
                                jwt: jwt,
                                pgn: chess.pgn(),
                                msg: 'win',
                                id: gameId
                            }))
                            modal.style.display = "block";
                            $("#title-modal").html("You won:<br>You are a winner");
                        }
                    }

                })
            }
        }
        return result
    }
}
/**
 * Every time the page is refreshed it will send a message with 
 * the request of a new game. The server will be able to tell if the
 * player is already in an existing match or if he isn't
 */
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
var i = 0 // counter used to set the orientation of the board
/**
 * This portion of the code handles when the server sends a message and the message is 
 * arrived
 * @param {CallableFunction} e 
 */
socket.onmessage = function (e) {
    var data = JSON.parse(e.data)
    /**
     * If disconnect is received it means that a login with the same account
     * from another device was made
     */
    if(data.disconnect === "disconnect"){
        /**
         * Sends an ajax request requesting the logout
         */
        $.ajax({
            type: "GET",
            url: "logout",
            success: function (response) {
                location.href = "login?de"
            }
        });
    }
    if(data.state === "Opponent resigned"){
        modal.style.display = "block";
        $("#title-modal").html("You won:<br>Your opponent resigned");
    }
    if(data.state === "Opponent requested draw"){
        $("#draw-accept-dialog").removeClass("hidden");
        $("#yes-accept-draw").click(function (e) { 
            e.preventDefault();
            socket.send(JSON.stringify({
                request: 'vsPlayer',
                state: 'finish',
                jwt: jwt,
                pgn: chess.pgn(),
                msg: 'accept-draw',
                id: gameId
            }))
            $("#draw-accept-dialog").addClass("hidden");
            $("#title-modal").html("You draw:<br>You accepted the draw offer");
            modal.style.display = "block";
            console.log("I accepted")
        });
    }
    if(data.state === "Opponent accepted the draw"){
        $("#title-modal").html("You draw:<br>Your opponent accepted the draw");
        modal.style.display = "block";
        console.log("Opponent accepted")
    }

    gameId = data.id_game
    console.log(data)
    try {
        if (data.status == "ready to play") { // this message is sent if the player was already in a game or a second player joined his game
            /**
             * Makes an ajax request to retriev information about the opponent. An id of the opponent
             * is sent at the beginning of a match.
             * Sets the image and username and the headers for the pgn of the game
             */
            $.ajax({
                type: "GET",
                url: "player/" + data.id_opponent,
                dataType: "json",
                success: function (response) {
                    if (data.color === 'white') {
                        chess.header("White", username, 'Black', response.username)
                    } else {
                        chess.header("White", response.username, 'Black', username)
                    }
                    console.log(chess.pgn())
                    $("#board").removeClass("hidden");
                    $(".ring").remove();
                    $(".username-opponent").html(response.username);
                    $(".img-opponent").attr("src", response.avatar);
                    $(".img-player").removeClass("hidden");
                    $(".username-player").removeClass("hidden");
                    $("#board").removeClass("hidden");
                    $(".opponent").removeClass("hidden");
                    $(".player").removeClass("hidden");
                }
            });


        }
    } catch (error) {
        console.log(error)
    }
    /**
     * The orientation and the enable of the move needs to be done only one time
     */
    if (i === 0) {
        $("#review-game").attr("href", "recap/" + gameId);
        color = data.color === 'white' ? COLOR.white : COLOR.black
        board.setOrientation(color)
        board.enableMoveInput(inputHandler, color) //enable the input for the color of the user
        i++
    }

    /**
     * Handles the position and the chess object. Needs to be wrapped in a try catch beacuse
     * the pgn is not sent if the player is seatching another player resulting in an exception 
     * thrown by the chess object
     */
    try {
        chess.loadPgn(data.pgn)
        board.setPosition(chess.fen(), true)
        $("#board").removeClass("hidden");
        $(".ring").addClass("hidden");
        if (chess.isGameOver()) {

            if (chess.isCheckmate()) {
                modal.style.display = "block";
                $("#title-modal").html("You lost:<br>Your opponent was stronger or luckier");
            } else {
                modal.style.display = "block";
                $("#title-modal").html("You drew:<br>You are a not a winner nor a loser");
            }
        }
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



}
/**
 * Sends a socket message telling the server to delete the game the player created 
 * Mostly used if the player is wating for a long time
 */
$("#back-menu").click(function (e) {
    e.preventDefault();
    socket.send(JSON.stringify({
        request: 'vsPlayer',
        state: 'delete',
        jwt: jwt
    }))
    location.href = "home"

})
/**
 * Get the informaton about the player himself. They are not visible until a 
 * player has been found
 */
$.ajax({
    type: "GET",
    url: "player/" + idPlayer,
    dataType: "json",
    success: function (response) {
        console.log(response)
        $(".username-player").html(response.username);
        $(".img-player").attr("src", response.avatar);
        username = response.username
    }
})
var modal = document.getElementById("modal");




// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
$("#yes-surrender").click(function(e) {
    e.preventDefault();
    $("#surrender-dialog").addClass("hidden");
    socket.send(JSON.stringify({
        request: 'vsPlayer',
        state: 'finish',
        jwt: jwt,
        pgn: chess.pgn(),
        msg: 'lost',
        id: gameId
    }))
    $("#title-modal").html("You lost:<br>You resigned like a coward");
    modal.style.display = "block";
});
$("#no-surrender").click(function (e) { 
    e.preventDefault();
    $("#surrender-dialog").addClass("hidden");
});

$("#yes-draw").click(function(e) {
    e.preventDefault();
    $("#draw-dialog").addClass("hidden");
    socket.send(JSON.stringify({
        request: 'vsPlayer',
        state: 'request-draw',
        jwt: jwt,
        pgn: chess.pgn(),
        id: gameId
    }))
});
$("#no-draw").click(function (e) { 
    e.preventDefault();
    $("#draw-dialog").addClass("hidden");
});
$("#no-accept-draw").click(function (e) { 
    e.preventDefault();
    $("#draw-accept-dialog").addClass("hidden");
});

