<?php

namespace refaltor\stats\Async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class SaveDataMySql extends AsyncTask
{
    public string $name;
    public array $data;
    public array $mysql;

    public function __construct($name, array $array, $mysql)
    {
        $this->name = $name;
        $this->data = $array;
        $this->mysql = $mysql;
    }

    public function onRun()
    {
        $save = true; // soon
        $data = base64_encode(serialize($this->data));
        $db = new \mysqli($this->mysql['hostname'], $this->mysql['username'], $this->mysql['password'], $this->mysql['database'], $this->mysql['port']);
        $name = \SQLite3::escapeString($this->name);
        $db->query("DELETE FROM stats WHERE player = '$name'");
        $db->query("INSERT INTO stats (player, data) VALUES ('$name', '$data')");
        $db->close();
        $this->setResult($save);
    }

    public function onCompletion(Server $server)
    {
        $save = $this->getResult();
        if ($save) $server->getLogger()->info($this->name . " data save !");
    }
}