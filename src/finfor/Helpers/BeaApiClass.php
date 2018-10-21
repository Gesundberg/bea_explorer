<?php

namespace Finfor\Helpers;

//use \Simplon\Request\Request as MyRequest;

class BeaApiClass {
    
    private $url;
    
    private $userId;
    
    private $method;
    
    private $data;
    
    public function __construct($url, $key, $method){
        
        $this->url = $url;
        $this->userId = $key;
        $this->setMethod($method);
        
    }

    public function setMethod($method){
        
        $this->method = $method;
        
    }
    
    public function getMethod(){
        
        return $this->method;
        
    }
    
    public function formData($params = null){
        
        $this->data = array(
            
            'UserID'    =>  $this->userId,
            'method'    =>  $this->getMethod()  
                                
        );
        
        if(isset($params)){
            
            $this->data = array_merge($this->data, $params);
            
        }
        
    }
    
    public function getJson(){
        
        // if method = GETDATASETLIST
        $req = new \Simplon\Request\Request();
        
        //$response = new \Simplon\Request\RequestResponse();
    
        $response = $req->get($this->url, $this->data);

        $code = $response->getHttpCode();
    	
        if( ($code==301) || ($code==302) || ($code==307) ){
            $location = $response->getHeader()->getLocation();
	        $location = preg_replace('/data\?/i', 'data/?', $location);
            $response = $req->get($location);
            
            //echo $response->getHeader()->getContentType().'<hr/>';
            //echo $response->getContent();
            
            if($response->getHeader()->isJson()){
                $json_str = $response->getContent();
                
                $json_str_format = $response->getHeader()->getCharset();
                
                $str = mb_convert_encoding($json_str, "UTF-8", $json_str_format);
            
                $json = json_decode($str, true);
            
                //print_r($json['BEAAPI']['Results']['Dataset']);
            
                //$answer = '';
                
                switch($this->getMethod()){
                    
                    case 'GETDATASETLIST' :
                        $answer = $this->fetchDatasets($json);
                        break;
                    
                    case 'getparameterlist':
                        $answer = $this->fetchParameterList($json);
                        break;
                    
                    default:
                        $answer = $str;
                                
                }

                if(isset($json['BEAAPI']['Results'])){
                    $answer = $json['BEAAPI']['Results'];
                }

                return $answer;
            }
        }
        
        return null;
                
        
    }
    
    public function fetchDatasets(&$json){
        
        $result = $json['BEAAPI']['Results']['Dataset'];
            
        $json_pre = array();
            
        foreach($result as $v){
            $json_pre[] = array(
                            'name'          =>  $v['DatasetName'] ,
                            'description'   =>  $v['DatasetDescription']
            );
                
        }
                
        //$json_pre = json_encode($json_pre);
                
        return $json_pre;
        
    }
    
    
    public function fetchParameterList(&$json){
        try{
        $result = $json['BEAAPI']['Results']['Parameter'];
        }
        catch (\Exception $e) {
            $result2 = $json['BEAAPI']['Results'];
            //$result2 = json_encode($result2);
            return $result2;
        }


        if (!isset($result[0])){
            $result = array($result);
            return $result;
            //return json_encode($result);
        }

        //return json_encode($result);
        return $result;
        
    }
    
}







