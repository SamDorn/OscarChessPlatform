<?php

namespace App\controllers;

class PvpController
{
    private $pvpInProgressModel;

    public function __construct($pvpInProgressModel)
    {
        $this->pvpInProgressModel = $pvpInProgressModel;
    }
    /**
     * Undocumented function
     *
     * @param [type] $username_1
     * @param [type] $color
     * @return void
     */
    public function setUsername_1($username_1, $color)
    {
        $this->pvpInProgressModel->setUsername_1($username_1,$color);
    }
    /**
     * Undocumented function
     *
     * @param [type] $username_2
     * @param [type] $color
     * @return void
     */
    public function setUsername_2($username_2, $color)
    {
        $this->pvpInProgressModel->setUsername_2($username_2,$color);
    }
    /**
     * Undocumented function
     *
     * @param [type] $username_1
     * @param [type] $color
     * @return void
     */
    public function setStatus($status)
    {
        $this->pvpInProgressModel->setStatus($status);
    }
    /**
     * Undocumented function
     *
     * @param [type] $pgn
     * @return void
     */
    public function setPgn($pgn)
    {
        $this->pvpInProgressModel->setPgn($pgn);
    }
}
?>