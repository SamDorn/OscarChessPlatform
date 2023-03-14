/**
 * Author and copyright: Stefan Haack (https://shaack.com)
 * Repository: https://github.com/shaack/cm-chessboard
 * License: MIT, see file 'LICENSE'
 */

export const EXTENSION_POINT = {
    positionChanged: "positionChanged", // the positions of the pieces was changed
    boardChanged: "boardChanged", // the board (orientation) was changed
    moveInputToggled: "moveInputToggled", // move input was enabled or disabled
    moveInput: "moveInput", // move started, to validate or canceled
    redrawBoard: "redrawBoard", // called after redrawing the board
    animation: "animation", // called on every animation frame
    destroy: "destroy" // called, before the board is destroyed
}

export class Extension {

    constructor(chessboard, props) {
        this.chessboard = chessboard
        this.props = props
    }

    registerExtensionPoint(name, callback) {
        if (!this.chessboard.state.extensionPoints[name]) {
            this.chessboard.state.extensionPoints[name] = []
        }
        this.chessboard.state.extensionPoints[name].push(callback)
    }

    registerMethod(name, callback) {
        if (!this.chessboard[name]) {
            this.chessboard[name] = (...args) => {
                return callback.apply(this, args)
            }
        } else {
            log.error("method", name, "already exists")
        }
    }

}
