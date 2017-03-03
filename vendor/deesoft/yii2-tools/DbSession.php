<?php

namespace dee\tools;

/**
 * Description of DbSession
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DbSession extends \yii\web\DbSession
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
        if ($this->db->schema->getTableSchema($this->sessionTable) === null) {
            $cmd = $this->db->createCommand();
            $cmd->createTable($this->sessionTable, [
                'id' => 'CHAR(40) PRIMARY KEY',
                'expire' => 'integer',
                'data' => 'binary',
            ])->execute();
        }
    }
}
