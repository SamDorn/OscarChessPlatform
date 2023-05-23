var socket = new WebSocket('ws://localhost:8080')
function connect(request){
    if (socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify({
            request: request,
            jwt: jwt
        }))

    }
    else {
        socket.addEventListener('open', function () {
            socket.send(JSON.stringify({
                request: request,
                jwt: jwt
            })
            )
        })
    }
}
