<?php

namespace refaltor\stats\Async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class SaveDataSQlite extends AsyncTask
{
    public string $name;
    public \SQLite3 $db;
    public array $data;

    public function __construct($name, $folder, array $array)
    {
        $this->name = $name;
        $this->db = new \SQLite3($folder . 'data/stats.db');
        $this->data = $array;
    }

    public function onRun()
    {
        $save = true; // soon
        $data = base64_encode(serialize($this->data));
        $name = \SQLite3::escapeString($this->name);
        $this->db->exec("DELETE FROM stats WHERE player = '$name'");
        $this->db->exec("INSERT INTO stats (player, data) VALUES ('$name', '$data')");
        $this->db->close();
        $this->setResult($save);
    }

    public function onCompletion(Server $server)
    {
        $save = $this->getResult();
        if ($save) $server->getLogger()->info($this->name . " data save !");
    }
}