<?php

class Luce_IndexController extends Omeka_Controller_AbstractActionController
{
    
    public function accessionSearchAction()
    {
        $accessionNum = trim($this->getParam('accession'));
        $itemParams = array('advanced' =>
                array(
                    array('terms' => $accessionNum,
                          'element_id' => 43,
                          'type' => 'is exactly'
                        )
                    )
        
        );
        print_r($itemParams);
        $items = $this->_helper->db->getTable('Item')->findBy($itemParams);
        if(!empty($items)) {
            $item = $items[0];
            echo metadata($item, 'id');
            $url = record_url($item, 'show');
            $url = str_replace('/lucehackathon', '', $url);
            $this->redirect($url);
        }
    }

    public function suggestAction()
    {
        $possibleTypes = array(
                'test',
                'nickname',
                'story',
                'creator',
                'simple',
                'theme',
                'recentView',
                'recentQuery'
                
                );
        $index = rand(0, count($possibleTypes) -1);
        $type = $this->getParam('type');
        if(!$type) {
            $suggestionType = $possibleTypes[$index];
        }
                
        $suggestionType = 'recentView';
        
        switch($suggestionType) {
            case 'test':
                $item = get_record_by_id('Item', 2);
                $typeHtml = "<p>Just testing</p>";
                break;
            case 'nickname':
                
                break;
                
            case 'story':
                
                break;
                
            case 'creator':
                
                break;
                
            case 'simple':
                
                break;
                
            case 'theme':
                
                break;
                
            case 'recentView':
                $recentView = unserialize(get_option('luce_recent_view'));
                $time = $recentView['time'];
                $item = get_record_by_id('Item', $recentView['id']);
                $typeHtml = "<p>Someone here was looking at this at $time. Why not look, too? </p>";
                break;
        }
        $collection = get_collection_for_item($item);
        $luceCase = luce_get_case_for_collection($collection);
        $itemHtml = "";
        $itemHtml .= $typeHtml;
        $itemHtml .= "<div id='item-info' style='float: left'>";
        $itemHtml .= "<p id='item-title'>" . metadata($item, array('Dublin Core', 'Title')) . "</p>";
        $itemHtml .= "<p id='item-location'>Give a look at ";
        $itemHtml .= link_to($collection, 'show', "Case " . metadata($collection, array('Dublin Core', 'Title')));
        $itemHtml .= ", floor " . $luceCase->floor; 
        $itemHtml .= "</p>";
        $itemHtml .= "</div>";
        $files = $item->Files;
        $itemHtml .= file_markup($files[0]);
        
        $itemHtml .= "</div>";
        $this->view->html = $itemHtml;
    }
    
    public function testAction()
    {

    }
    
}