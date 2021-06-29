<?php

namespace refaltor\stats\Provider;

use pocketmine\Player;

interface ProviderInterface
{
    /**
     * Prepare database selected
     */
    public function prepare(): void;


    /**
     * Record the player’s data.
     * If the 'force' parameter is set to true, then the player even if it exists will be re-created.
     *
     * @param Player $player
     * @param bool $force
     */
    public function registerUser(Player $player, bool $force = false): void;


    /**
     * Saves the user’s data in the database.
     *
     * @param Player $player
     */
    public function saveUser(Player $player): void;


    /**
     * Allows to have all the statistical data of the player.
     *
     * @param Player $player
     * @return array
     */
    public function getAllStats(Player $player): array;


    /**
     * Allows to have the number of kill that the player has done.
     *
     * @param Player $player
     * @return int
     */
    public function getKills(Player $player): int;


    /**
     * Allows to have the number of death of the player.
     *
     * @param Player $player
     * @return int
     */
    public function getDeaths(Player $player): int;


    /**
     * Allows to have the number of block broken by the player,
     * if you want you can have the number of block broken from a specific ID (id:meta).
     *
     * @param Player $player
     * @param null $id
     * @return int
     */
    public function getBlockBreak(Player $player, $id = null): int;



    /**
     * Allows to have the number of block placce by the player,
     * if you want you can have the number of block broken from a specific ID (id:meta).
     *
     * @param Player $player
     * @param null $id
     * @return int
     */
    public function getBlockPlace(Player $player, $id = null): int;


    /**
     * Allows you to have the player’s playing time in seconds,
     * after you convert this time into hour, into years ...
     *
     * @param Player $player
     * @return int
     */
    public function getTime(Player $player): int;
}