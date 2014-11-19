<?php
/**
 * Template functions for DokuBook template
 * 
 * @license:    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author:     Michael Klier <chi@chimeric.de>
 */
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_LF')) define('DOKU_LF', "\n");

// load language files
require_once(DOKU_TPLINC.'lang/en/lang.php');
if(@file_exists(DOKU_TPLINC.'lang/'.$conf['lang'].'/lang.php')) {
    require_once(DOKU_TPLINC.'lang/'.$conf['lang'].'/lang.php');
}

/**
 * checks if a file called logo.png or logo.jpg exists
 * and uses it as logo, uses the dokuwiki logo by default
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function tpl_logo() {
    global $conf;
    global $INFO;
    
    $out = '';

    switch(true) {
        case(@file_exists(DOKU_TPLINC.'images/logo.jpg')):
            $logo = DOKU_TPL.'images/logo.jpg';
            break;
        case(@file_exists(DOKU_TPLINC.'images/logo.jpeg')):
            $logo = DOKU_TPL.'images/logo.jpeg';
            break;
        case(@file_exists(DOKU_TPLINC.'images/logo.png')):
            $logo = DOKU_TPL.'images/logo.png';
            break;
        default:
            $logo = DOKU_TPL.'images/dokuwiki-128.png';
            break;
    }
    $lang_specific_start_string = 'doku.php/' . $conf['lang'].':'.$conf['start'];
    $out .= '<a href="' . DOKU_BASE . '" name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[ALT+H]">';
    $out .= '  <img class="logo" src="' . $logo . '" alt="' . $conf['title'] . '" /></a>' . DOKU_LF;

    print ($out);
}

/**
 * generates the sidebar contents
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function tpl_sidebar() {
    global $lang;
    global $ID;
    global $INFO;

    if(tpl_getConf('closedwiki') && empty($INFO['userinfo'])) {
        print '<div id="toolbox" class="sidebar_box">' . DOKU_LF;
        print '<h1>Toolbox</h1>' . DOKU_LF;
        tpl_actionlink('login');
        print '</div>' . DOKU_LF;
        return;
    }

    // main navigation
    // print '<span class="sb_label">' . $lang['navigation'] . '</span>' . DOKU_LF;
    print '<div id="navigation" class="sidebar_box">' . DOKU_LF;
    print '<h1>' . $lang['Navigation'] . '</h1>' . DOKU_LF;
    print '<hr style="text-align: left; width:90%; margin: -0.2ex 0 0.9ex 0;" />' . DOKU_LF;
    $USER_INFO=$INFO['userinfo'];
    $USER_NAME=$USER_INFO['name'];
    $USER_STRING=str_replace(" ","_",strtolower($USER_NAME));
    $USER_HOME=DOKU_BASE . 'doku.php/en:user:' . $USER_NAME . ':' . $USER_NAME;
    $URL_HELP=DOKU_BASE . 'doku.php/en:help:help';
    $URL_USERS=DOKU_BASE . 'doku.php/en:user:user';
    //$URL_BLOG=DOKU_BASE . 'doku.php/blog:blog';
    $doku_src = DOKU_TPL.'images/sidebar_logo.png';
    $user_src = DOKU_TPL.'images/user.gif';
    $users_src = DOKU_TPL.'images/sidebar_users.png';
    $blog_src = DOKU_TPL.'images/sidebar_blog.png';
    $help_src = DOKU_TPL.'images/sidebar_help.png';

    print '<a class="sidebar_nav_link" href="' . DOKU_BASE . '">'.DOKU_LF;
    print '  <img style="vertical-align: middle;" src="' . $doku_src . '" />' . $lang['Doku Doodles'].DOKU_LF;
    print '</a>'.DOKU_LF;
    print '<br/>'.DOKU_LF;
    $userhomepage_plugin = &plugin_load('action','userhomepage');
    if ( $userhomepage_plugin ) {
        if ( plugin_isdisabled($userhomepage_plugin->getPluginName() ) ) {
            $userhomepage_plugin == NULL;
	}

    }
    if ( ( strlen($USER_NAME) != 0 ) && ( $userhomepage_plugin != NULL ) ) {
        print '<a class="sidebar_nav_link" href="' . $USER_HOME . '">'.DOKU_LF;
        //print '  <img style="vertical-align: middle;" src="' . $user_src . '" />' . $lang['${USER_NAME}'];
        print '  <img style="vertical-align: middle;" src="' . $user_src . '" />' . $USER_NAME.DOKU_LF;
        print '</a>'.DOKU_LF;
        print '<br/>'.DOKU_LF;
        print '<a class="sidebar_nav_link" href="' . $URL_USERS . '">'.DOKU_LF;
        print '  <img style="vertical-align: middle;" src="' . $users_src . '" />' . $lang['Users'].DOKU_LF;
        print '</a>'.DOKU_LF;
        print '<br/>'.DOKU_LF;
    }
    //print '<a class="sidebar_nav_link" href="' . $URL_BLOG . '">';
    //print '  <img style="vertical-align: middle;" src="' . $blog_src . '" />' . $lang['Blog'];
    //print '</a>';
    //print '<br/>';
    print '<a class="sidebar_nav_link" href="' . $URL_HELP . '">'.DOKU_LF;
    print '  <img style="vertical-align: middle;" src="' . $help_src . '" />' . $lang['Help'].DOKU_LF;
    print '</a>'.DOKU_LF;
    print '<hr style="text-align: left; width:90%; margin: 0.3ex 0 0.5ex 0;" />' . DOKU_LF;

    $svID  = cleanID($ID);
    $navpn = tpl_getConf('sb_pagename');
    
    // Get the user's custom navigation page.


    if ( strlen($USER_NAME) != 0 ) {
        $users_namespace = tpl_getConf('users_namespace');
        $user_navpn = $users_namespace.':'.$USER_STRING.':custom_' . $navpn;
        $found =  @file_exists(wikiFN($user_navpn));

        if($found && auth_quickaclcheck($user_navpn) >= AUTH_READ) {
            print '<h1>' . $lang['bookmarks'] . '</h1>' . DOKU_LF;
            print '<hr style="text-align: left; width:90%; margin: 0.3ex 0 0.5ex 0;" />' . DOKU_LF;
            print p_dokubook_xhtml($user_navpn);
            print '<hr style="text-align: left; width:90%; margin: 0.3ex 0 0.5ex 0;" />' . DOKU_LF;
        } else { // make the page
	    $user_navpn_file = DOKU_INC.'data/pages/'.str_replace(":","/",$user_navpn).'.txt';
	    $handle = fopen($user_navpn_file,'w');
	    $content = ' * [['.$USER_STRING.'|Home Page]]';
	    fwrite($handle,$content);
	}
    }

    /**************************************************************************
     * Check for the local language's navigation page
     *************************************************************************/
    // Get the closest navigation page or else print the index.

    // $svID is the current wiki page path + filename, e.g. 
    //     programming:cpp:topics:bitwise
    global $conf;

    $path  = explode(':',$svID);
    array_pop($path); // remove the page name, just get the path
    $found = false;
    $sb    = '';

    // Dont want to go all the way back, just to the iso_lang embedded ns
    // as we consider these the 'root', hence below is > 1, not 0
    while(!$found && count($path) > 1) {
        $sb = implode(':', $path) . ':' . $navpn;
	if(@file_exists(wikiFN($sb)) && auth_quickaclcheck($sb) >= AUTH_READ) {
       	    print '<h1>' . $lang['localmap'] . '</h1>' . DOKU_LF;
	    print '<hr style="text-align: left; width:90%; margin: 0.3ex 0 0.5ex 0;" />';
	    print p_dokubook_xhtml($sb);
	    print '<hr style="text-align: left; width:90%; margin: 0.3ex 0 0.5ex 0;" />';
	    $found = true;
	    continue;
	} else {
	    //print p_index_xhtml(cleanID($svID));
	}
        //$found =  @file_exists(wikiFN($sb));
        array_pop($path);
    }
  
    /**************************************************************************
     * Check for the root language's navigation page
     *************************************************************************/
    $la_navpn = $conf['lang'].":".$navpn;
    $found =  @file_exists(wikiFN($la_navpn));
    if ( $found ) {
        $sb = $la_navpn;
    }
    /**************************************************************************
     * If lang root failed, check for the default root navigation page
     *************************************************************************/
    if ( !$found ) {
        $found = @file_exists(wikiFN($navpn));
	if ( $found ) {
            $sb = $navpn;
	}
    }
 
    if(@file_exists(wikiFN($sb)) && auth_quickaclcheck($sb) >= AUTH_READ) {
       print '<h1>' . $lang['globalmap'] . '</h1>' . DOKU_LF;
       print '<hr style="text-align: left; width:90%; margin: 0.3ex 0 0.5ex 0;" />';
       print p_dokubook_xhtml($sb);
    } else {
        print p_index_xhtml(cleanID($svID));
    }

    print '</div>' . DOKU_LF;

    // generate the searchbox
     //print '<span class="sb_label">' . strtolower($lang['btn_search']) . '</span>' . DOKU_LF;
     //print '<div id="search">' . DOKU_LF;
     //tpl_searchform();
     //print '</div>' . DOKU_LF;

    // generate the toolbox
    print '<div id="toolbox" class="sidebar_box">' . DOKU_LF;
    print '<h1>' . $lang['Toolbox'] . '</h1>' . DOKU_LF;
    print '<hr style="text-align: left; width:90%; margin: -0.2ex 0 0.9ex 0;" />';
    tpl_actionlink('admin');
    tpl_actionlink('index');
    tpl_actionlink('recent');
    tpl_actionlink('backlink');
    tpl_actionlink('profile');
    tpl_actionlink('login');
    print '</div>' . DOKU_LF;

    // restore ID just in case
    $Id = $svID;
}

/**
 * prints a custom page footer
 *
 * @author Michael Klier <chi@chimeric.de>
 */
function tpl_footer() {
    global $ID;

    $svID  = $ID;
    $ftpn  = tpl_getConf('ft_pagename');
    $path  = explode(':',$svID);
    $found = false;
    $ft    = '';

    while(!$found && count($path) > 0) {
        $ft = implode(':', $path) . ':' . $ftpn;
        $found =  @file_exists(wikiFN($ft));
        array_pop($path);
    }

    if(!$found && @file_exists(wikiFN($ftpn))) $ft = $ftpn;

    if(@file_exists(wikiFN($ft)) && auth_quickaclcheck($ft) >= AUTH_READ) {
        print '<div id="footer">' . DOKU_LF;
        print p_dokubook_xhtml($ft);
        print '</div>' . DOKU_LF;
    }

    // restore ID just in case
    $ID = $svID;
}

/**
 * removes the TOC of the sidebar-pages and shows 
 * a edit-button if user has enough rights
 * 
 * @author Michael Klier <chi@chimeric.de>
 */
function p_dokubook_xhtml($wp) {
    if(auth_quickaclcheck($wp) >= AUTH_EDIT) {
        $data = '<div class="sidebar-secedit">' . html_btn('secedit',$wp,'',array('do'=>'edit','rev'=>'','post')) . '</div>';
    }
    $data .= p_wiki_xhtml($wp,'',false);
    // strip TOC
    $data = preg_replace('/<div class="toc">.*?(<\/div>\n<\/div>)/s', '', $data);
    // replace headline ids for XHTML compliance
    $data = preg_replace('/(<h.*?><a.*?id=")(.*?)(">.*?<\/a><\/h.*?>)/','\1sb_\2\3', $data);
    return ($data);
}

/**
 * Renders the Index
 *
 * copy of html_index located in /inc/html.php
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Michael Klier <chi@chimeric.de>
 */
function p_index_xhtml($ns) {
  require_once(DOKU_INC.'inc/search.php');
  global $conf;
  global $ID;
  $dir = $conf['datadir'];
  $ns  = cleanID($ns);
  #fixme use appropriate function
  if(empty($ns)){
    $ns = dirname(str_replace(':','/',$ID));
    if($ns == '.') $ns ='';
  }
  $ns  = utf8_encodeFN(str_replace(':','/',$ns));

  // only extract headline
  preg_match('/<h1>.*?<\/h1>/', p_locale_xhtml('index'), $match);
  print $match[0];

  $data = array();
  search($data,$conf['datadir'],'search_index',array('ns' => $ns));

  print '<div id="sb__index__tree">' . DOKU_LF;
  print html_buildlist($data,'idx','html_list_index','html_li_index');
  print '</div>' . DOKU_LF;
}

// vim:ts=2:sw=2:enc=utf-8:
