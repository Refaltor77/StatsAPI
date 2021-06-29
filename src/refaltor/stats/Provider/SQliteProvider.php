<?php

namespace refaltor\stats\Provider;

use pocketmine\Player;
use pocketmine\Server;
use refaltor\stats\Async\SaveDataSQlite;
use refaltor\stats\Main;
use SQLite3;

class SQliteProvider implements ProviderInterface
{
    public SQLite3 $db;
    public array $cache = [];

    public function prepare(): void
    {
        $this->db = new SQLite3(Main::getInstance()->getDataFolder() . 'data/stats.db');
        $this->db->exec("CREATE TABLE IF NOT EXISTS stats (player VARCHAR(255), data VARCHAR(255))");
    }

    public function registerUser(Player $player, bool $force = false): void
    {
        $name = $player->getName();
        if ($force) {
            $this->cache[$name] = ['break' => [], 'place' => [], 'kill' => 0, 'death' => 0, 'time' => 0];
        }else {
            $str = Sqlite3::escapeString($name);
            $bool = $this->db->query("SELECT player FROM stats WHERE player = '$str'");
            if (!$bool->numColumns() > 0) {
                $this->cache[$name] = ['break' => [], 'place' => [], 'kill' => 0, 'death' => 0, 'time' => 0];
            }else{
                $data = $this->db->query("SELECT data FROM stats WHERE player = '$str'");
                $data = unserialize(base64_decode($data->fetchArray()[0]));
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
        $name = $player->getName();
        $folder = Main::getInstance()->getDataFolder();
        $data = $this->cache[$name];
        Server::getInstance()->getAsyncPool()->submitTask(new SaveDataSQlite($name, $folder, $data));
    }
}