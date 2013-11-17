<?php

class LuceCollection extends LuceRecord
{
    
    public function edanAdd($params)
    {
        if(!isset($params['name'])) {
            throw new LuceApiException('collection name must be set');
        }
        
        $params['type'] = 'add';
        $client = $this->getEdanClient('collection', $params);
        $response = $client->request();
        if($response->getStatus() == 200) {
            $json = json_decode($response->getBody(), true);
            $this->edan_id = $json['record_ID'];
            $this->save();            
        }
    }

    
    public function edanDelete($params)
    {
        
    }

    protected function beforeSave($args)
    {
        $metadata = array('public' => true);
        $post = $args['post'];
        
        $elementTexts = array('Dublin Core' => 
            array('Title' => $post['title'])        
                
                
        );
        $collection = insert_collection($metadata, $elementTexts);
    }
}