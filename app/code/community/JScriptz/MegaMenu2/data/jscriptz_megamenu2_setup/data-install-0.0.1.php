<?php
/**
 * JScriptz_MegaMenu2 extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category   	JScriptz
 * @package	JScriptz_MegaMenu2
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 */
 /*
 * @category	JScriptz
 * @package	JScriptz_MegaMenu2
 */
Mage::getModel('megamenu2/menuitem')
	->load(1)
	->setParentId(0)
	->setPath(1)
	->setLevel(0)
	->setPosition(0)
	->setChildrenCount(0)
	->setLinktitle('ROOT')
	->setInitialSetupFlag(true)
	->save();
Mage::getModel('megamenu2/menuitem')->load(2)->setLinktitle('Products')->setParentId(1)->setLinktype(1)->setPath(1)->setLinkimage('/p/r/prod_icon.png')->setLevel(0)->setLinkclass('topnav-link')->setPosition(1)->setChildrenCount(0)->setStatus(1)->save();	
Mage::getModel('megamenu2/menuitem')->load(3)->setLinktitle('jQuery Tabs')->setParentId(1)->setLinktype(2)->setPath(1)->setLevel(0)->setPosition(3)->setChildrenCount(0)->setStatus(1)->setLinkclass('topnav-link')->setMenuhtml("<div class='dropdown_fullwidth mgmenu_tabs' style='display: none;'><!-- Begin Item Container -->
                

                    <ul class='mgmenu_tabs_nav'>

                        <li><a class='current' href='#section1'>Columns Grid</a></li>
                        <li><a href='#section2'>Gallery</a></li>
                        <li><a href='#section3'>About Us</a></li>
                        <li><a href='#section4'>Informations</a></li>

                    </ul>
                    
                    <div class='mgmenu_tabs_panels'><!-- Begin Panels Container -->
                    
                        <div id='section1'><!-- Begin Section 1 -->
                        
                            <div class='col_12'>
                                <h4>This is a full width container</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris venenatis bibendum nunc dapibus posuere. Sed quis laoreet justo. Mauris eu massa turpis, at blandit elit. Mauris rutrum placerat libero, ut rhoncus leo euismod non. Aliquam urna felis, rutrum eu rhoncus at, elementum id est.</p>
                            </div>

                            <div class='col_10'>
                                <h4>This is a five sixths container</h4>
                                <p>Phasellus bibendum malesuada augue et adipiscing. Ut pretium vulputate elit quis iaculis. Nulla nisi justo, rhoncus in consectetur et, posuere sed urna. Aliquam urna felis, rutrum eu rhoncus at, elementum id est.</p>
                            </div>

                            <div class='col_2'>
                                <h4>1/6</h4>
                                <p>Fusce adipiscing consequat porta.</p>
                            </div>

                            <div class='col_8'>
                                <h4>This is a two thirds container</h4>
                                <p>Nunc scelerisque nisl id purus pretium cursus. Integer sed auctor elit. Pellentesque malesuada suscipit vehicula. Pellentesque malesuada suscipit vehicula.</p>
                            </div>

                            <div class='col_4'>
                                <h4>1/3 container</h4>
                                <p>Integer sed auctor elit. Pellentesque malesuada suscipit vehicula urna felis.</p>
                            </div>

                            <div class='col_6'>
                                <h4>This is a half width container</h4>
                                <p>Donec vel egestas lorem. Cras at purus turpis. Fusce a imperdiet mauris. Nunc lobortis neque magna, nec iaculis nisl.</p>
                            </div>

                            <div class='col_6'>
                                <h4>This is a half width container</h4>
                                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras pharetra tincidunt.</p>
                            </div>
			    <div style='clear:both'></div>
                            <div class='col_4'>
                                <h4>1/3 container</h4>
                                <p>Nunc in lectus nec erat adipiscing ultrices. Donec ac scelerisque neque.</p>
                            </div>

                            <div class='col_4'>
                                <h4>1/3 container</h4>
                                <p>Fusce sapien ante, convallis eu sodales malesuada, porttitor in nisi.</p>
                            </div>

                            <div class='col_4'>
                                <h4>1/3 container</h4>
                                <p>Mauris faucibus lectus accumsan, placerat tortor nec, volutpat mi.</p>
                            </div>

                            <div class='col_3'>
                                <h4>1/4 container</h4>
                                <p>Phasellus eleifend, eros at pharetra consequat.</p>
                            </div>

                            <div class='col_3'>
                                <h4>1/4 container</h4>
                                <p>Vestibulum sit amet est turpis convallis eu sodales.</p>
                            </div>

                            <div class='col_3'>
                                <h4>1/4 container</h4>
                                <p>Ut id fermentum nunc, non adipiscing diam.</p>
                            </div>

                            <div class='col_3'>
                                <h4>1/4 container</h4>
                                <p>Suspendisse eros faucibus, in luctus ante porta.</p>
                            </div>
                        
                        </div><!-- End Section 1 -->
                        
                        <div class='mgmenu_tabs_hide' id='section2'><!-- Begin Section 2 -->

                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product01@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>
                            
                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product02@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>
                            
                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product03@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>

                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product04@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>
                            
                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product05@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>
                            
                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product06@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>

                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product07@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>
                            
                            <div class='col_3'>
                                <a href='#'><img width='220' height='140' class='inline_img' alt='' src='/skin/frontend/base/default/images/jscriptz_megamenu2/product08@2x.jpg'></a>
                                <p class='img_description'>Image Description</p>
                            </div>                    
                        
                        </div><!-- End Section 2 -->
                        
                        <div class='mgmenu_tabs_hide' id='section3'><!-- Begin Section 3 -->
                        
                            <div class='col_12'>
                                
                                <h3>About Us</h3>

                                <p>Fusce adipiscing consequat porta. Proin porta molestie mauris in imperdiet. Aliquam erat volutpat. Phasellus elementum accumsan bibendum. Nulla metus massa, sagittis non aliquam quis, mollis ac arcu. Praesent adipiscing mauris ultricies nisl egestas congue molestie nunc aliquet. In faucibus euismod sapien vitae consectetur. Integer nec ligula nisi, et pretium mi. In non porttitor tortor. Donec vel egestas lorem.</p>

                                <blockquote>'This is a testimonial from a customer. Donec vel egestas lorem. Cras at purus turpis. Fusce a imperdiet mauris faucibus euismod sapien vitae consectetur.'</blockquote>

                                <p>Nunc scelerisque nisl id purus pretium cursus. Integer sed auctor elit. Pellentesque malesuada suscipit vehicula. Nunc dapibus, eros nec posuere rhoncus, elit lorem elementum libero, nec tempor purus neque nec ipsum. Mauris bibendum lectus nec orci pharetra dignissim egestas interdum nibh. Nunc adipiscing felis quis nunc malesuada ac ultrices mi luctus. Maecenas a porta libero. In ut rhoncus quam. Sed nec vestibulum mauris.</p>
                                
                                <blockquote>'Phasellus elementum accumsan bibendum. Nulla metus massa, sagittis non aliquam quis, mollis ac arcu.'</blockquote>

                                <p>Cras at purus turpis. Fusce a imperdiet mauris. Nunc lobortis neque magna, nec iaculis nisl. Quisque at leo erat, a pretium ante. Nunc vel pretium diam. Aliquam erat volutpat.</p>

                            </div>
                        
                        </div><!-- End Section 3 -->
                        
                        <div class='mgmenu_tabs_hide' id='section4'><!-- Begin Section 4 -->
                        
                            <div class='col_12'>
                                
                                <h3>Additional Informations</h3>

                            </div>

                            <div class='col_6'>

                                <p>Phasellus bibendum malesuada augue et adipiscing. Ut pretium vulputate elit quis iaculis. Nulla nisi justo, rhoncus in consectetur et, posuere sed urna. Aliquam urna felis, rutrum eu rhoncus at, elementum id est. Ut cursus elementum nisi eu elementum. Sed lacus purus.</p>

                                <p>Integer nisl nunc, venenatis sagittis condimentum vel, tincidunt in est. Aenean felis sem, suscipit in posuere ultrices, placerat vel ipsum. Donec quis dolor turpis, non accumsan nisl. Ut lorem turpis, consequat eget condimentum quis, consectetur vitae enim. Proin ultricies ornare nibh eget tincidunt. Nulla id lectus est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras pharetra tincidunt erat sit amet sodales.</p>

                            </div>

                            <div class='col_6 col_border'>

                                <p>Fusce adipiscing consequat porta. Proin porta molestie mauris in imperdiet. Aliquam erat volutpat. Phasellus elementum accumsan bibendum. Nulla metus massa, sagittis non aliquam quis, mollis ac arcu. Praesent adipiscing mauris ultricies nisl egestas congue molestie nunc aliquet. In faucibus euismod sapien vitae consectetur. Integer nec ligula nisi, et pretium mi. In non porttitor tortor. Donec vel egestas lorem. Cras at purus turpis. Fusce a imperdiet mauris.</p>

                                <p>Nunc lobortis neque magna, nec iaculis nisl. Quisque at leo erat, a pretium ante. Ullamcorper ut aliquet ut, dictum nec odio. Donec sed odio ac lectus fermentum accumsan quis ut magna. Sed consectetur ipsum dolor, non laoreet lectus. Phasellus malesuada varius molestie.</p>

                            </div>                    
                        
                        </div><!-- End Section 4 -->
                    
                    </div><!-- End Panels Container -->

                                
                </div><!-- End Item Container -->

            
            </li><!-- End Item -->")->save();


Mage::getModel('megamenu2/menuitem')->load(4)->setParentId(1)->setLinktype(2)->setLinktitle('Video Example')->setPath(1)->setLevel(0)->setPosition(4)->setChildrenCount(0)->setStatus(1)->setLinkclass('topnav-link')->setMenuhtml("<div class='dropdown_container dropdown_5columns' style='display: none;'><!-- Begin Item Container -->


                    <div class='col_12'>

                        <h4>Awesome Video !</h4>
                        <p>Ut ligula nibh, tincidunt et dolor vitae, bibendum lectus, et tempus lorem lobortis consectetur neque.</p>
                        <div class='video_container'>
                            <iframe src='http://player.vimeo.com/video/32001208?portrait=0&amp;badge=0'></iframe>
                        </div>

                    </div>


                </div><!-- End Item Container -->")->save();

Mage::getModel('megamenu2/menuitem')->load(5)->setParentId(1)->setLinktype(0)->setLinktitle('Link Example')->setLinkpath('http://www.gobingya.com')->setLinkclass('topnav-link')->setLinktarget('blank')->setPath(1)->setLevel(0)->setPosition(5)->setChildrenCount(0)->setStatus(1)->save();
Mage::getModel('megamenu2/menuitem')->load(6)->setParentId(1)->setLinktype(4)->setLinktitle('Featured')->setLinkpath('')->setLinkclass('topnav-link')->setLinktarget('self')->setMenuhtml("<h4 class=\"featured\">Featured Products</h4>")->setPath(1)->setLevel(0)->setPosition(2)->setChildrenCount(0)->setStatus(1)->save();
Mage::getModel('megamenu2/menuitem')->load(7)->setParentId(1)->setLinktype(3)->setLinktitle('Contact Us')->setPath(1)->setLevel(0)->setPosition(6)->setChildrenCount(0)->setStatus(1)->save();

Mage::getModel('megamenu2/menusetting')->load(7)
	->setMenutitle('Main Menu')
	->setdisablemenu(0)
	->save();
	
Mage::getModel('megamenu2/menuitem_menusetting')->load(8)
	->setMenuitemId(2)
	->setMenusettingId(1)
	->setPosition(0)
	->save();
Mage::getModel('megamenu2/menuitem_menusetting')->load(9)
	->setMenuitemId(3)
	->setMenusettingId(1)
	->setPosition(1)
	->save();
Mage::getModel('megamenu2/menuitem_menusetting')->load(10)
	->setMenuitemId(4)
	->setMenusettingId(1)
	->setPosition(3)
	->save();
Mage::getModel('megamenu2/menuitem_menusetting')->load(11)
	->setMenuitemId(5)
	->setMenusettingId(1)
	->setPosition(4)
	->save();
Mage::getModel('megamenu2/menuitem_menusetting')->load(12)
	->setMenuitemId(6)
	->setMenusettingId(1)
	->setPosition(5)
	->save();
Mage::getModel('megamenu2/menuitem_menusetting')->load(13)
	->setMenuitemId(7)
	->setMenusettingId(1)
	->setPosition(2)
	->save();