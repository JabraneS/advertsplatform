<?php

namespace JOMANEL\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JOMANELUserBundle extends Bundle {

	public function getParent(){
    
    	return 'FOSUserBundle';
    }//fnc
    
}//class
