<?php
    
    @$status = $data->status;
        @$gameId = $data->gameId;
        @$fen = $data->fen;
        @$chatMsg = $data->chatMsg;



        if ($gameId == null) { //if gameId is null he's just landed on the page
            /**
             * Search the first game that has no second player
             */
            foreach ($this->games as $game) {
                if ($game->players[1] == null) {
                    $this->gameNoSecondPlayer = $game;
                    break;
                }
            }
            /**
             * if the game witout second player is not null it will insert the 
             * player so that the match can start
             */
            if (!is_null($this->gameNoSecondPlayer)) {
                $this->gameNoSecondPlayer->insertPlayer($from);
                $this->games[] = $this->gameNoSecondPlayer;
                if (is_null($this->gameNoSecondPlayer->black))
                    $color = "black";
                else
                    $color = "white";

                $this->gameNoSecondPlayer->players[1]->send(json_encode(array(
                    'color' => $color
                )));
                foreach ($this->gameNoSecondPlayer->players as $player) {
                    $player->send(json_encode(array(
                        'gameId' => $this->gameNoSecondPlayer->getId(),
                        'status' => "ready to play"
                    )));
                }
                //$this->gameNoSecondPlayer->setstatus;
                $this->gameNoSecondPlayer = null;
                //$this->pvpController->addSecondUsername();
                echo "Inserted in an existing game\n";
            }
            /**
             * if the game without a second player is null it means that there are no 
             * games without the second player so it will create a new game.
             */
            else {
                //$this->pvpController->createGame();
                $game = new ChessGame($from);
                $this->games[] = $game;
                if (is_null($game->black))
                    $color = "white";
                else
                    $color = "black";
                $from->send(json_encode(array(
                    "status" => "searching for a second player",
                    "color" => $color
                )));
                echo "Created a new game\n";
            }
        }
        /**
         * if the game id is not null it takes the game with the same unique id 
         * and then sends the updated fen position.
         */
        else {
            foreach ($this->games as $game) {
                if ($game->getId() == $gameId) {
                    $this->prova = $game;
                    break;
                }
            }
            $this->prova->updateFen($fen);
            if ($status != null && $status == "game_over") {
                foreach ($this->prova->players as $player) {
                    $player->send(json_encode(array(
                        "status" => "game terminated"
                    )));
                }
                $this->games = array_filter($this->games, function ($i) use ($gameId) {
                    return $i->getId() != $gameId;
                });
                echo count($this->games);
            } else {
                foreach ($this->prova->players as $player) {
                    $player->send(json_encode(array(
                        'gameId' => $this->prova->getId(),
                        'fen' => $this->prova->fen
                    )));
                }
            }
        }