<?php

class LuceRecord extends Omeka_Record_AbstractRecord
{
    public $record_id;
    public $record_type;
    public $edan_id;
    public $edan_api_url;
    protected $edan_data;
    protected $record;

    
    public function edanGet($id = null)
    {
        if($id) {
            $params = array('q'=>'record_ID:saam_' . $id);
        } else {
            $params = array('q'=>'record_ID:saam_' . $this->edan_id);
        }
        
        $client = $this->getEdanClient('metadata', $params);
        $response = $client->request();
        if($response->getStatus() == 200) {
            $this->setEdanData(json_decode($response->getBody(), true));
        } else {
            echo $response->getStatus();
        }
    }    
    
    public function setEdanData($data)
    {
        $this->edan_id = $data['response']['docs'][0]['descriptiveNonRepeating']['record_ID'];
        $this->edan_data = $data;
    }
    
    public function getEdanData()
    {
        return $this->edan_data;
    }
    
    public function edanToElementTexts()
    {
        $elementTexts = array('Dublin Core' => array('Title' => array(), 
                'Description' => array()));
        
        $title = $this->findDcElement('title');
        $elementTexts['Dublin Core']['Title'][] = array('text'=>$title, 'html'=>0 );
        
        return $elementTexts;
    }
    
    public function setRecord($record)
    {
        $this->record_id = $record->id;
        $this->record_type = get_class($record);
        $this->record = $record;
    }
    
    public function getRecord()
    {
        return $this->record;
    }
    
    public function findDcElement($element) {
        $baseArray = $this->edan_data['response']['docs'][0]['descriptiveNonRepeating'];
        return $baseArray['title']['content'];
    }
    
    protected function getEdanClient($service, $params = array())
    {
        $params['wt'] = 'json';
        $client = new Zend_Http_Client(LUCE_EDAN_BASE . "{$service}Service");
        $client->setParameterGet($params);
        $client->setAuth(LUCE_USER, LUCE_PASSWORD);
        $client->setParameterGet('applicationId', 'LuceHackfest-PMJ');
        return $client;
    }
    
}