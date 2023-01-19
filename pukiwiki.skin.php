<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// pukiwiki.skin.php
// Copyright
//   2002-2021 PukiWiki Development Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// PukiWiki default skin

// ------------------------------------------------------------
// Settings (define before here, if you want)

// Set site identities
$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$_IMAGE['skin']['favicon']  = ''; // Sample: 'image/favicon.ico';

// SKIN_DEFAULT_DISABLE_TOPICPATH
//   1 = Show reload URL
//   0 = Show topicpath
if (!defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
    define('SKIN_DEFAULT_DISABLE_TOPICPATH', 0); // 1, 0

// Show / Hide navigation bar UI at your choice
// NOTE: This is not stop their functionalities!
if (!defined('PKWK_SKIN_SHOW_NAVBAR'))
    define('PKWK_SKIN_SHOW_NAVBAR', 1); // 1, 0

// Show / Hide toolbar UI at your choice
// NOTE: This is not stop their functionalities!
if (!defined('PKWK_SKIN_SHOW_TOOLBAR'))
    define('PKWK_SKIN_SHOW_TOOLBAR', 0); // 1, 0

// ------------------------------------------------------------
// Code start

// Prohibit direct access
if (!defined('UI_LANG')) die('UI_LANG is not set');
if (!isset($_LANG)) die('$_LANG is not set');
if (!defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = &$_LANG['skin'];
$link  = &$_LINK;
$image = &$_IMAGE['skin'];
$rw    = !PKWK_READONLY;

// MenuBar
$menu = arg_check('read') && exist_plugin_convert('menu') ? do_plugin_convert('menu') : FALSE;
// RightBar
$rightbar = FALSE;
if (arg_check('read') && exist_plugin_convert('rightbar')) {
    $rightbar = do_plugin_convert('rightbar');
}
// ------------------------------------------------------------
// Output

// HTTP headers
pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CONTENT_CHARSET ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php if ($nofollow || !$is_read) { ?>
        <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>
    <?php if ($html_meta_referrer_policy) { ?>
        <meta name="referrer" content="<?php echo htmlsc(html_meta_referrer_policy) ?>" /><?php } ?>

    <title><?php echo $title ?> - <?php echo $page_title ?></title>

    <link rel="SHORTCUT ICON" href="<?php echo $image['favicon'] ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo SKIN_DIR ?>pukiwiki.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SKIN_DIR ?>MaterialPukiWiki/assets/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SKIN_DIR ?>MaterialPukiWiki/assets/css/style.css" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Noto Sans JP -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $link['rss'] ?>" /><?php // RSS auto-discovery 
                                                                                                        ?>
    <script type="text/javascript" src="skin/main.js" defer></script>
    <script type="text/javascript" src="skin/search2.js" defer></script>

    <?php echo $head_tag ?>
</head>

<body>
    <?php echo $html_scripting_data ?>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $link['top']; ?>">
                <?php echo $page_title; ?>
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col mt-3">
                <div class="d-flex justify-content-between align-items-center">
                <?php if (!SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
                <?php 
                function get_parent_links() {
                    require_once(PLUGIN_DIR . 'topicpath.inc.php');
                    global $vars, $defaultpage;
                    $page = isset($vars['page']) ? $vars['page'] : '';
                    if ($page === '' || $page === $defaultpage) return [];
                    $links = plugin_topicpath_parent_links($vars['page']);
                    return $links;
                }
                $links = get_parent_links();
                ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <?php
                        foreach ($links as $l) {
                        ?>
                        <li class="breadcrumb-item"><a href="<?php echo $l['uri'] ?>"><?php echo $l['leaf'] ?></a></li>
                        <?php
                        }
                        ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo end(explode('/', $title)) ?></li>
                    </ol>
                </nav>
                <?php } ?>
                <?php 
                if (PKWK_SKIN_SHOW_NAVBAR) {
                    function _navigator($key, $icon='', $value = '', $javascript = '')
                    {
	                    $lang = & $GLOBALS['_LANG']['skin'];
	                    $link = & $GLOBALS['_LINK'];
                        if (!isset($lang[$key])) {
                            // echo 'LANG NOT FOUND';
                            return FALSE;
                        }
                        if (!isset($link[$key])) {
                            // echo 'LINK NOT FOUND';
                            return FALSE;
                        }
                        if ($icon !== '')  {
                            $icon = '<i class="'. $icon .' me-2"></i>';
                        }

                        echo '<li><a class="dropdown-item" href="' . $link[$key] . '" ' . $javascript . '>' . $icon . (($value === '') ? $lang[$key] : $value) . '</a></li>';

                        return TRUE;
                    }
                ?>
                <div class="dropdown">
                    <button
                        class="btn btn-primary btn-sm dropdown-toggle hidden-arrow"
                        type="button"
                        id="dropdownMenuButton"
                        data-mdb-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="fa-solid fa-bars fa-lg"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php 
                    if ($is_page) {
                        if ($rw) {
                            _navigator('edit', 'fa-solid fa-pen-to-square');
                            if ($is_read && $function_freeze) {
                                (!$is_freeze) ? _navigator('freeze', 'fa-solid fa-lock') : _navigator('unfreeze', 'fa-solid fa-unlock');
                            }
                        }
                        _navigator('diff', 'fa-solid fa-code-compare');
                        if ($do_backup) {
                            _navigator('backup', 'fa-solid fa-clock-rotate-left');
                        }
                        if ($rw && (bool)ini_get('file_uploads')) {
                            _navigator('upload', 'fa-solid fa-cloud-arrow-up');
                        }
                        _navigator('reload', 'fa-solid fa-rotate-right');
                        _navigator('rename', 'fa-solid fa-pen');
                    }
                    ?>
                    <?php if ($is_page) { ?>
                    <li><hr class="dropdown-divider" /></li>
                    <?php } ?>
                    <?php 
                    if ($rw) {
                        _navigator('new', 'fa-solid fa-plus');
                    }
                    _navigator('list', 'fa-solid fa-list');
                    if (arg_check('list')) {
                        _navigator('filelist', 'fa-solid fa-list');
                    }
                    _navigator('search', 'fa-solid fa-magnifying-glass');
                    _navigator('recent', 'fa-solid fa-clock');
                    _navigator('help', 'fa-solid fa-circle-info');
                    if ($enable_login) {
                        _navigator('login', 'fa-solid fa-right-to-bracket');
                    }
                    if ($enable_logout) {
                        _navigator('logout', 'fa-solid fa-right-from-bracket');
                    }
                    ?>
                    </ul>
                </div>
                <?php } ?>
                </div>
                <?php if (PKWK_SKIN_SHOW_NAVBAR || !SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
                <hr>
                <?php } ?>
                    <?php echo $body ?>
                    <?php if ($notes != '') { ?>
                        <div id="note"><?php echo $notes ?></div>
                    <?php } ?>
                    <?php if ($attaches != '') { ?>
                        <div id="attach">
                            <?php echo $hr ?>
                            <?php echo $attaches ?>
                        </div>
                    <?php } ?>
                </div>
            <?php if ($menu) { ?>
                <hr class="d-block d-md-none mt-3 mb-0">
                <div class="col-md-3 order-md-first mt-3">
                    <?php echo $menu ?>
                </div>
            <?php } ?>
            <?php if ($rightbar) { ?>
                <hr class="d-block d-md-none mt-3 mb-0">
                <div class="col-md-3 mt-3">
                    <?php echo $rightbar ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <footer class="bg-secondary bg-opacity-10 py-3 mt-3">
        <div class="text-center">
            <?php if ($is_page) { ?>
                <?php if (SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
                    <a href="<?php echo $link['canonical_url'] ?>"><span class="small"><?php echo $link['canonical_url'] ?></span></a>
                    <br>
                <?php } ?>
            <?php } ?>
            <?php if ($lastmodified != '') { ?>
            <span>Last-modified: <?php echo $lastmodified ?></span>
            <br>
            <?php } ?>
            <!--
            <?php if ($related != '') { ?>
            <span>Link: <?php echo $related ?></span>
            <br>
            <?php } ?>
            -->
            <span>Site admin: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a></span>
            <br>
            <!-- Copyright -->
            <span><?php echo S_COPYRIGHT ?>.</span>
            <span>Powered by PHP <?php echo PHP_VERSION ?>.</span>
            <span>HTML convert time: <?php echo elapsedtime() ?> sec.</span>
        </div>

    <?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
        <!-- Toolbar -->
        <div id="toolbar" class="d-flex justify-content-end mt-1">
            <?php

            // Set toolbar-specific images
            $_IMAGE['skin']['reload']   = 'reload.png';
            $_IMAGE['skin']['new']      = 'new.png';
            $_IMAGE['skin']['edit']     = 'edit.png';
            $_IMAGE['skin']['freeze']   = 'freeze.png';
            $_IMAGE['skin']['unfreeze'] = 'unfreeze.png';
            $_IMAGE['skin']['diff']     = 'diff.png';
            $_IMAGE['skin']['upload']   = 'file.png';
            $_IMAGE['skin']['copy']     = 'copy.png';
            $_IMAGE['skin']['rename']   = 'rename.png';
            $_IMAGE['skin']['top']      = 'top.png';
            $_IMAGE['skin']['list']     = 'list.png';
            $_IMAGE['skin']['search']   = 'search.png';
            $_IMAGE['skin']['recent']   = 'recentchanges.png';
            $_IMAGE['skin']['backup']   = 'backup.png';
            $_IMAGE['skin']['help']     = 'help.png';
            $_IMAGE['skin']['rss']      = 'rss.png';
            $_IMAGE['skin']['rss10']    = &$_IMAGE['skin']['rss'];
            $_IMAGE['skin']['rss20']    = 'rss20.png';
            $_IMAGE['skin']['rdf']      = 'rdf.png';

            function _toolbar($key, $x = 20, $y = 20)
            {
                $lang  = &$GLOBALS['_LANG']['skin'];
                $link  = &$GLOBALS['_LINK'];
                $image = &$GLOBALS['_IMAGE']['skin'];
                if (!isset($lang[$key])) {
                    echo 'LANG NOT FOUND';
                    return FALSE;
                }
                if (!isset($link[$key])) {
                    echo 'LINK NOT FOUND';
                    return FALSE;
                }
                if (!isset($image[$key])) {
                    echo 'IMAGE NOT FOUND';
                    return FALSE;
                }

                echo '<a href="' . $link[$key] . '">' .
                    '<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" ' .
                    'alt="' . $lang[$key] . '" title="' . $lang[$key] . '" />' .
                    '</a>';
                return TRUE;
            }
            ?>
            <?php _toolbar('top') ?>

            <?php if ($is_page) { ?>
                &nbsp;
                <?php if ($rw) { ?>
                    <?php _toolbar('edit') ?>
                    <?php if ($is_read && $function_freeze) { ?>
                        <?php if (!$is_freeze) {
                            _toolbar('freeze');
                        } else {
                            _toolbar('unfreeze');
                        } ?>
                    <?php } ?>
                <?php } ?>
                <?php _toolbar('diff') ?>
                <?php if ($do_backup) { ?>
                    <?php _toolbar('backup') ?>
                <?php } ?>
                <?php if ($rw) { ?>
                    <?php if ((bool)ini_get('file_uploads')) { ?>
                        <?php _toolbar('upload') ?>
                    <?php } ?>
                    <?php _toolbar('copy') ?>
                    <?php _toolbar('rename') ?>
                <?php } ?>
                <?php _toolbar('reload') ?>
            <?php } ?>
            &nbsp;
            <?php if ($rw) { ?>
                <?php _toolbar('new') ?>
            <?php } ?>
            <?php _toolbar('list')   ?>
            <?php _toolbar('search') ?>
            <?php _toolbar('recent') ?>
            &nbsp; <?php _toolbar('help') ?>
            &nbsp; <?php _toolbar('rss10', 36, 14) ?>
        </div>
    <?php } // PKWK_SKIN_SHOW_TOOLBAR 
    ?>
    </footer>

    <script type="text/javascript" src="<?php echo SKIN_DIR ?>MaterialPukiWiki/assets/js/mdb.min.js"></script>
</body>

</html>
