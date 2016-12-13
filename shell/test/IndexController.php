<?php


class IndexController
{
    /*默认这个控制器制定的layout*/
    //public $_layOut='layout.pc'; //默认就是指向layout.pc

    public function indexAction(){
        return array('a'=>date('H-y-d H:i:s'),'my_yes'=>date('H-y-d H:i:s'));
    }
}
