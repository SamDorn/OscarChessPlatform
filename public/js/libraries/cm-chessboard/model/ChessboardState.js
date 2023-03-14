/**
 * Author and copyright: Stefan Haack (https://shaack.com)
 * Repository: https://github.com/shaack/cm-chessboard
 * License: MIT, see file 'LICENSE'
 */
import {Position} from "./Position.js"

export function createTask() {
    let resolve, reject
    const promise = new Promise(function (_resolve, _reject) {
        resolve = _resolve
        reject = _reject
    })
    promise.resolve = resolve
    promise.reject = reject
    return promise
}

export class ChessboardState {

    constructor() {
        this.position = new Position()
        this.orientation = undefined
        this.markers = []
        this.inputWhiteEnabled = false
        this.inputBlackEnabled = false
        this.inputEnabled = false
        this.squareSelectEnabled = false
        this.extensionPoints = {}
        this.moveInputProcess = createTask().resolve()
    }

    setPosition(fen, animated = false) {
        this.position = new Position(fen, animated)
    }

    movePiece(fromSquare, toSquare, animated = false) {
        const position = this._position.clone()
        position.animated = animated
        const piece = position.getPiece(fromSquare)
        if (!piece) {
            console.error("no piece on", fromSquare)
        }
        position.setPiece(fromSquare, undefined)
        position.setPiece(toSquare, piece)
        this._position = position
    }

    setPiece(square, piece, animated = false) {
        const position = this._position.clone()
        position.animated = animated
        position.setPiece(square, piece)
        this._position = position
    }

    invokeExtensionPoints(name, data = {}) {
        const extensionPoints = this.extensionPoints[name]
        const dataCloned = Object.assign({}, data)
        dataCloned.extensionPoint = name
        let returnValue = true
        if (extensionPoints) {
            for (const extensionPoint of extensionPoints) {
                if(extensionPoint(dataCloned) === false) {
                    returnValue = false
                }
            }
        }
        return returnValue
    }

}
