<?php
namespace josegonzalez\Queuesadilla\Engine;

use DateInterval;
use DateTime;
use PDO;
use PDOException;
use josegonzalez\Queuesadilla\Engine\PdoEngine;

class MysqlEngine extends PdoEngine
{

    /**
     *  String used to start a database identifier quoting to make it safe
     *
     * @var string
     */
    protected $startQuote = '`';

    /**
     * String used to end a database identifier quoting to make it safe
     *
     * @var string
     */
    protected $endQuote = '`';

    protected $baseConfig = [
        'delay' => null,
        'database' => 'database_name',
        'expires_in' => null,
        'user' => null,
        'pass' => null,
        'port' => 3306,
        'priority' => 0,
        'queue' => 'default',
        'attempts' => 0,
        'attempts_delay' => 600,
        'host' => '127.0.0.1',
        'table' => 'jobs',
    ];

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        $config = $this->settings;
        if (empty($config['flags'])) {
            $config['flags'] = [];
        }

        $flags = [
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ] + $config['flags'];

        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";

        $limit = 10;
        $counter = 0;
        while (true) {
            try {
                $this->connection = new PDO(
                    $dsn,
                    $config['user'],
                    $config['pass'],
                    $flags
                );
                break;
            } catch (PDOException $e) {
                $this->connection = null;
                unset($this->connection);
                $counter++;
                if ($counter == $limit) {
                    $this->logger()->error($e->getMessage());
                    $this->connection = null;
                }
            }
        }
        return (bool)$this->connection;
    }
}
