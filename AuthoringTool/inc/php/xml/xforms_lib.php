<?php

class xforms_lib{
    var $namespace;
    var $namespaceXforms;
    var $namespaceEvents;
    var $allowed;
    var $tag;
    var $print;
    var $indentation;
    var $indentationValue;
    
    function xforms_lib($ns, $nsxforms, $nsevents){
        $this->namespace = $ns;
        $this->namespaceXforms = $nsxforms;
        $this->namespaceEvents = $nsevents;
        $this->print = 0;
        $this->indentation = 0;
        $this->indentationValue = "    ";
        $this->tag = '';
        $this->allowed =
            array('action' => array('dispatch', 'insert',
                                    'setvalue', 'load'),
                  'model' => array('instance', 'submission',
                                   'bind', 'action'),
                  'trigger' => array('label', 'action'),
                  'root' => array('trigger', 'submit', 'select1',
                                  'repeat', 'input', 'output',
                                  'label', 'model'));
    }

    function setTag($t){
        $this->tag = $t;
    }

    function incrementIndentation(){
        $this->indentation++;
    }

    function decrementIndentation(){
        $this->indentation--;
    }

    function indentation(){
        $xml = '';
        for($i = $this->indentation; $i > 0; $i--)
            $xml .= $this->indentationValue;
        return $xml;
    }

    function setPrint($p){
        if($p)
            $this->print = 1;
        else
            $this->print = 0;
    }
    
    function check($newTag, $action=''){
        $openTag = $this->tag;
        if($openTag == '')
            $openTag = 'root';

        if($action == 'close' && ($openTag != $newTag || $openTag == 'root'))
            throw new Exception("Cannot close $openTag with a $newTag ".
                                "\"close tag\"statement!");
        else if($action == 'close' && $openTag == $newTag)
            return;
        else if(!in_array($newTag, $this->allowed["$openTag"]))
            throw new Exception("$newTag is not allowed within $openTag!");
    }

    function printData($xml){
        if($this->print){
            echo $this->indentation();
            echo $xml."\r\n";
            return 0;
        }
        return 1;
    }

    function doctypeTag(){
        $xml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $this->printData($xml);
        return $xml;
    }

    function headTitle($title){
        $xml = "<head><title>$title</title>";
        $this->printData($xml);
        return $xml;
    }

    function closeHeadOpenBody(){
        $xml = "</head><body>";
        $this->printData($xml);
        return $xml;
    }

    function closeBodyCloseHtml(){
        $xml = "</body></html>";
        $this->printData($xml);
        return $xml;
    }

    function outputXHTMLheader(){
        header("Content-Type: application/xhtml+xml; charset=UTF-8");
    }

    function htmlTag($customNS='', $customNSnamespace=''){
        $xml = '<html';
        if($this->namespace != '')
            $xml .= ' xmlns="'.$this->namespace.'"';
        if($this->namespaceXforms != '')
            $xml .= ' xmlns:xforms="'.$this->namespaceXforms.'"';
        if($this->namespaceEvents != '')
            $xml .= ' xmlns:ev="'.$this->namespaceEvents.'"';
        if($this->customNS != '' && $this->customNSnamespace != '')
            $xml .= ' xmlns:'.$this->customNS.
                '="'.$this->customNSnamespace.'"';
        $xml .= ' >';
        $this->printData($xml);
        return $xml;
    }

    function submissionTag($id, $action, $method = 'post', $ref='',
                           $instance = '', $replace = ''){
        $this->check('submission');
        if(($instance != '' || $replace != '') &&
           $replace != '' && $instance != '')
            throw new Exception("instance or replace is set, but not both ".
                                "in a submission tag!");
        $xml = '<xforms:submission id="'.$id.'" action="'.$action.
            '" method="'.$method.'"';
        if($ref != '')
            $xml .= ' ref="'.$ref.'"';
        if($instance != '')
            $xml .= ' instance="'.$instance.'"';
        if($replace != '')
            $xml .= ' replace="'.$replace.'"';
        $xml .= " />";
        $this->printData($xml);
        return $xml;
    }

    function bindTag($nodeset, $relevant = '', $calculate = '',
                     $required = ''){
        $this->check('bind');
        if($relevant == '' && $calculate == '' && $required == '')
            throw new Exception("Must set one of: relevant, calculate or ".
                                "required in a bind tag!");
        $xml = '<xforms:bind nodeset="'.$nodeset.'"';
        if($relevant != '')
            $xml .= ' relevant="'.$relevant.'"';
        if($calculate != '')
            $xml .= ' calculate="'.$calculate.'"';
        $xml .= " />";
        $this->printData($xml);
        return $xml;
    }

    function dispatchTag($name, $target){
        $this->check('dispatch');
        $xml = '<xforms:dispatch';
        if($name != '')
            $xml .= ' name="'.$name.'"';
        if($target != '')
            $xml .= ' target="'.$target.'"';
        $xml .= " />";
        $this->printData($xml);
        return $xml;
    }

    function loadTag($resource = '', $ref = '', $show='replace'){
        $this->check('load');
        if($resource != '' && $ref != '')
            throw new Exception("Cannot specify both a resource and ref ".
                                "for the load tag!");
        else if($resource == '' && $ref == '')
            throw new Exception("Must specify one of: resource or ref ".
                                "for the load tag!");
        $xml = '<xforms:load show="'.$show.'"';
        if($ref != '')
            $xml .= ' ref="'.$ref.'"';
        else if($resource != '')
            $xml .= ' resource="'.$resource.'"';
        $xml .= " />";
        $this->printData($xml);
        return $xml;
    }

    function insertTag($nodeset, $at, $position = 'after'){
        $this->check('insert');
        $xml = '<xforms:insert nodeset="'.$nodeset.'" at="'.$at.
            '" position="'.$position.'" />';
        $this->printData($xml);
        return $xml;
    }

    function setvalueTag($ref, $value){
        $this->check('setvalue');
        $xml = '<xforms:setvalue ref="'.$ref.'" value="'.$value.'" />';
        $this->printData($xml);
        return $xml;
    }

    function inputTag($ref, $label = ''){
        $this->check('input');
        $xml = '<xforms:input ref="'.$ref.'">';
        if($label != '')
            $xml .= '<xforms:label>'.$label.'</xforms:label>';
        $xml .= "</xforms:input>";
        $this->printData($xml);
        return $xml;
    }

    function outputTag($value){
        $this->check('output');
        $xml = '<xforms:output value="'.$value.'">';
        $xml .= '</xforms:output>';
        $this->printData($xml);
        return $xml;
    }

    function select1Tag($ref, $label, $itemArray, $itemset){
        $this->check('select1');
        if(is_array($itemArray) && is_array($itemset))
            throw new Exception("Cannot specify both a list of items ".
                                "and an itemset for a select1 element!");
        else if(!is_array($itemArray) && !is_array($itemset))
            throw new Exception("Must specify one of: itemArray or itemset ".
                                "as arrays in the select1 tag!");
        $xml = '<xforms:select1 ref="'.$ref.'">';
        $xml .= '<xforms:label>'.$label.'</xforms:label>';
        if(is_array($itemset)){
            $xml .= '<xforms:itemset nodeset="'.$itemset['nodeset'].'">';
            $xml .= '<xforms:label ref="'.$itemset['label'].'" />';
            $xml .= '<xforms:value ref="'.$itemset['value'].'" />';
            $xml .= '</xforms:itemset>';
        }
        else if(is_array($itemArray))
            foreach($itemArray as $item){
                $xml .= '<xforms:item>';
                $xml .= '<xforms:label>'.$item['label'].'</xforms:label>';
                $xml .= '<xforms:value>'.$item['value'].'</xforms:value>';
                $xml .= '</xforms:item>';
            }
        $xml .= '</xforms:select1>';
        $this->printData($xml);
        return $xml;
    }

    function comment($comment){
        $xml = '<!-- '.$comment.' -->';
        $this->printData($xml);
        return $xml;
    }

    function instanceTag($id = '', $instanceXML = '', $src = ''){
        $this->check('instance');
        if($instanceXML != '' && $src != '')
            throw new Exception("Must define instance data or a src URL ".
                                "for the instance tag!");
        $xml = '<xforms:instance';
        if($id != '')
            $xml .= ' id="'.$id.'"';
        if($src != '')
            $xml .= ' src="'.$src.'"';
        if($instanceXML != ''){
            $xml .= '>'."\r\n";
            $xml .= $instanceXML;
            $xml .= "\r\n".$this->indentation().'</xforms:instance>';
        }
        else
            $xml .= " />";
        $this->printData($xml);
        return $xml;
    }

    function submitTag($submission, $label='Submit', $ref = ''){
        $this->check('submit');
        $xml = '<xforms:submit submission="'.$submission.'"';
        if($ref != '')
            $xml .= ' ref="'.$ref.'"';
        $xml .= ' >';
        $xml .= '<xforms:label>'.$label.'</xforms:label>';
        $xml .= '</xforms:submit>';
        $this->printData($xml);
        return $xml;
    }

    function actionTagOpen($event){
        $this->check('action', 'open');
        $xml = '<xforms:action ev:event="'.$event.'">';
        $this->incrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function actionTagClose(){
        $this->check('action', 'close');
        $xml = '</xforms:action>';
        $this->decrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function repeatTagOpen($nodeset, $id = ''){
        $this->check('repeat', 'open');
        $xml = '<xforms:repeat nodeset="'.$nodeset.'"';
        if($id != '')
            $xml .= ' id="'.$id.'"';
        $xml .= ' >';
        $this->incrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function repeatTagClose(){
        $this->check('repeat', 'close');
        $xml = '</xforms:repeat>';
        $this->decrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function modelTagOpen($id = ''){
        $this->check('model', 'open');
        $xml = '<xforms:model';
        if($id != '')
            $xml .= ' id="'.$id.'"';
        $xml .= ' >';
        $this->incrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function modelTagClose(){
        $this->check('model', 'close');
        $xml = '</xforms:model>';
        $this->decrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function triggerTagOpen($ref, $submission = '', $label = 'default'){
        $this->check('trigger', 'open');
        $xml = '<xforms:trigger ref="'.$ref.'"';
        if($submission != '')
            $xml .= ' submission="'.$submission.'"';
        $xml .= ' >';
        $xml .= '<xforms:label>'.$label.'</xforms:label>';
        $this->incrementIndentation();
        $this->printData($xml);
        return $xml;
    }

    function triggerTagClose(){
        $this->check('trigger', 'close');
        $xml = '</xforms:trigger>';
        $this->decrementIndentation();
        $this->printData($xml);
        return $xml;
    }

}

?>
