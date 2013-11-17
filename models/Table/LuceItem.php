<?php

class Table_LuceItem extends Omeka_Db_Table
{
    
    public function findByItem($item)
    {
        if(is_numeric($item)) {
            $itemId = $item;
        } else {
            $itemId = $item->id;
        }
        $select = $this->getSelect();
        $select->where('record_id = ?', $itemId);
        return $this->fetchObject($select);
    }
}