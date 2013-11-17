<?php

class LuceItem extends LuceRecord
{
    public $case_id;

    public function isOnDisplay()
    {
        if(!$this->edan_data) {
            $this->edanGet();
        }
        $val = $this->edan_data['response']['docs'][0]['indexedStructured']['onPhysicalExhibit'][0];
        if($val == 'Yes') {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateRecord()
    {
        $accessionNumber = metadata($this->record, array('Dublin Core', 'Identifier'));
        $elTexts = $this->edanToElementTexts();
        $metadata = array('collection_id' => $this->case_id, 'item_type_id' => 15);
        
        //make sure I never clobber the accession number
       // $elTexts['Dublin Core']['Identifier'][] = array('text' => $accessionNumber, 'html'=>0);
        //$this->record->deleteElementTexts();
        update_item($this->record, $metadata);
    }
    
    public function getImage($size)
    {
        $baseArray = $this->edan_data['response']['docs'][0]['descriptiveNonRepeating'];
        if(!empty($baseArray['online_media'])) {
            return $baseArray['online_media']['media'][0][$size];
        }
    }
    
    public function insertFiles()
    {
        $url = $this->getImage('content');
        insert_files_for_item($this->record, 'Url', array($url));  
    }
    
    public function parseLuceCase()
    {
        if(!$this->edan_data) {
            $this->edanGet();
        }
        $sets = $this->edan_data['response']['docs'][0]['freetext']['setName'];
        foreach($sets as $set) {
            $content = $set['content'];
            $matches = array();
            $pattern = '/\d\d\S/';
            preg_match($pattern, $content, $matches);
            if(!empty($matches)) {
                $label = $matches[0];
                $cases = $this->getTable('LuceCase')->findBy(array('label'=>$label));
                $case = $cases[0];
                $this->case_id = $case->id;
                //update_item($this->record, array('collection_id' => $case->collection_id));
            }            
        }
    }
}