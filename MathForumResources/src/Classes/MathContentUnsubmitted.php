<?php

class MathContentUnsubmitted {
    function __construct($content, $author_id) {
        $this->content = $content;
        $this->author_id = $author_id;
    }
    
    public $content;
    public $author_id;
    
    
    public function submit_content($conn) {
    }
}
    

//Brainstorm:
// Properties:
// content
// author
// subject, if post
// parent (the object)
// is_top, if response
//
// Method:
// submit_content

// submit_content: for responses, there will be two queries. For posts, just one.

?>