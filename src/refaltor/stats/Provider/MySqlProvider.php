<?php

namespace refaltor\stats\Provider;

use mysqli;
use pocketmine\Player;
use refaltor\stats\Main;
use SQLite3;

class MySqlProvider implements ProviderInterface
{
    public mysqli $db;
    public array $cache = [];

    public function prepare(): void
    {
        $array = Main::getInstance()->getConfig()->getAll()['mysql'];
        $this->db = new mysqli($array['hostname'], $array['username'], $array['password'], $array['database'], $array['port']);
        $this->db->query("CREATE TABLE IF NOT EXISTS stats (player VARCHAR(255), data VARCHAR(255))");
        $this->db->close();
    }

    public function registerUser(Player $player, bool $force = false): void
    {
        $name = $player->getName();
        if ($force) {
            $this->cache[$name] = ['break' => [], 'place' => [], 'kill' => 0, 'death' => 0, 'time' => 0];
        }else {
            $str = $this->db->real_escape_string($name);
            $bool = $this->db->query("SELECT player FROM stats WHERE player = '$str'");
            if (!$bool->num_rows > 0) {
                $this->cache[$name] = ['break' => [], 'place' => [], 'kill' => 0, 'death' => 0, 'time' => 0];
            }else{
                $data = $this->db->query("SELECT data FROM stats WHERE player = '$str'");
                $data = unserialize(base64_decode($data->fetch_array()[0]));
                $this->cache[$name] = $data;
            }
        }
    }

    public function getKills(Player $player): int
    {
        $name = $player->getName();
        return $this->cache[$name]['kill'];
    }

    public function getDeaths(Player $player): int
    {
        $name = $player->getName();
        return $this->cache[$name]['death'];
    }

    public function getTime(Player $player): int
    {
        $name = $player->getName();
        return $this->cache[$name]['time'];
    }

    public function getAllStats(Player $player): array
    {
        $name = $player->getName();
        return $this->cache[$name];
    }

    public function getBlockBreak(Player $player, $id = null): int
    {
        $name = $player->getName();
        if ($id === null){
            $i = 0;
            foreach ($this->cache[$name]['break'] as $id => $count){
                $i += $count;
            }
            return $i;
        }else return $this->cache[$name]['break'][$id];
    }

    public function getBlockPlace(Player $player, $id = null): int
    {
        $name = $player->getName();
        if ($id === null){
            $i = 0;
            foreach ($this->cache[$name]['place'] as $id => $count){
                $i += $count;
            }
            return $i;
        }else return $this->cache[$name]['place'][$id];
    }

    public function saveUser(Player $player): void
    {

    }
}