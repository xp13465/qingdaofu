<?php

namespace dee\tools;

/**
 * Description of DbCache
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DbCache extends \yii\caching\DbCache
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->createTable();
    }

    /**
     * Create table if not exists
     */
    protected function createTable()
    {
        if ($this->db->schema->getTableSchema($this->cacheTable) === null) {
            $this->db->createCommand()
                ->createTable($this->cacheTable, [
                    'id' => 'CHAR(128) PRIMARY KEY',
                    'expire' => 'integer',
                    'data' => 'binary',
                ])->execute();
        }
    }
}
