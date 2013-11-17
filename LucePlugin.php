<?php

require_once('defines.php');

class LucePlugin extends Omeka_Plugin_AbstractPlugin
{
    
    protected $_hooks = array('install', 
                               'uninstall',
                               'after_save_collection',
                               'public_content_top',
                               'public_head'
            );
    
    public function hookInstall($args)
    {
        $db = $this->_db;
        $query = "
            
            CREATE TABLE IF NOT EXISTS $db->LuceItem (
              `record_id` int(11) NOT NULL,
              `record_type` tinytext COLLATE utf16_unicode_ci NOT NULL,
              `edan_id` int(11) NOT NULL,
              `edan_api_url` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;            
        
        ";
        $db->query($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS $db->LuceCase (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `collection_id` int(11) NOT NULL,
              `label` tinytext COLLATE utf16_unicode_ci NOT NULL,
              `floor` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci AUTO_INCREMENT=1 ;
            ";
        $db->query($sql);
        
    }
    
    public function hookAfterSaveCollection($args)
    {
        debug('hook');
        $collection = $args['record'];
        $cases = $this->_db->getTable('LuceCase')->findBy(array('collection_id'=>$collection->id));
        if(empty($cases)) {
            $case = new LuceCase();
            $case->collection_id = $collection->id;
            $case->label = metadata($collection, array('Dublin Core', 'Title'));
            $case->save();
        }
    }
    
    public function hookPublicContentTop($args)
    {
        if(on_site('Luce')) {
            echo "<div id='accession_search' class='luce_search'>
            Looking at something? Would you like to know more? Enter the number on the tag here.
            <form  style='float: right'  method='get' action='/lucehackathon/luce/index/accession-search' >
            <input type='text' name='accession' /><button>Ask</button>
            </form>            
            </div>";
            
            echo "<br/>
            <div id='case_search' class='luce_search'>
                What's down this hall? Enter the case number on the side (e.g. 17B)
                <form  style='float: right'  method='get' action='/lucehackathon/luce/index/case-search' >
                <input type='text' name='accession' /><button>Ask</button>
                </form>                
            </div><br/>
            ";
            
        }
        
        
        
    }
    
    public function hookPublicHead($args)
    {
        queue_js_file('luce');
    }
    
    public function hookUninstall($args)
    {
        
    }
    
}