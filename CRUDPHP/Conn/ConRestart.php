<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BD;

/**
 * Description of ConRestart
 *
 * @author Adão José PC->cidinha
 * adao.jose123.a.r@gmail.com
 * fb.com/adao.jose123.a
 */
class ConRestart  extends Conn{
    public function __construct() {
        parent::restart();
    }
}
